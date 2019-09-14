<?php namespace App\Core;

use App\Core\Database;
use Rakit\Validation\Validator;

class Model
{
    protected $primary_key = "id";
    protected $table 	   = null;
    protected $fields      = [];
    protected $guarded     = [];
    protected $unique      = [];
    protected $rules       = [];
    protected $errorMessage= [
        "email"    => "ایمیل وارد شده صحیح نمی باشد.",
        "regex"    => "لطفا با زبان فارسی این فیلد را پر کنید.",
        "digits"   => "تعداد رقم های وارد شده مجاز نمی باشد.",
        "required" => "وارد کردن این مورد ضروری است.",
        "min"      => "تعداد کاراکتر وارد شده کمتر از حد مجاز است.",
        "max"      => "تعداد کاراکتر وارد شده بیشتر از حد مجاز است.",
        "numeric"  => "عدد وارد شده صحیح نمی باشد.",
        "alpha_dash"    => "تنها از فاصله ، آندرلاین ، خط فاصله ، عدد و حروف الفبا میتوانید استفاده کنید.",
        "alpha_spaces"  => "تنها از حروف الفا و فاصله میتوانید استفاده کنید.",
        "mimes"  => "فایل وارد شده مجاز نمی باشد.",
        "date"  => "تاریخ وارد شده معتبر نمی باشد.",
        "uploaded_file" => "تصویر وارد شده معتبر نمی باشد : حجم فایل باید کمتراز 500 کیلوبایت باشد",
    ];
    public $errors;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        Db::init();
        if($this->table === null)
        {
            $table_prefix = '';
            $this->table = $table_prefix . strtolower(ltrim(get_class($this),'App\\Models'));
        }
    }

    public function isNew()
    {
        return ($this->fields[$this->primary_key] == 0 || $this->fields[$this->primary_key] == null) ? true : false;
    }

    public static function all($from = -1,$to = -1,$order = null)
    {
        try
        {
            $limit = '';
            $bound = [];
            if ($from !== -1)
            {
                $limit = " LIMIT ?";

                if($order)
                {
                    $order = explode('|',$order);
                    $limit = ' ORDER BY '.$order[0].' '.$order[1].' LIMIT ?';
                }

                $bound = [$from];
                if($to !== -1)
                {
                    $limit .= ",?";
                    $bound[] = $to;
                }
            }
            $obj   = get_called_class();
            $obj   = new $obj;
            $sql   = "SELECT * FROM ".$obj->table.$limit;
            Db::$db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
            $stmt  = Db::$db->prepare($sql);
            $stmt->execute($bound);
        }
        catch(\PDOException $e)
        {
            showSystemError("cant find any thing" , $e->getMessage());
        }
        return self::convertToObj($stmt);
    }

    public function save()
    {
        $pk = $this->primary_key;

        if($this->isNew())
        {
            if($this->validate())
                try
                {
                    $files = $_FILES;
                    $this->uploadFiles($files);

                    $fields   = ' (';
                    $fields  .= implode(array_keys($this->fields),',');
                    $fields  .= ') ';
                    $fields2  = ' (:';
                    $fields2 .= implode(array_keys($this->fields),', :');
                    $fields2 .= ') ';

                    $sql = 'INSERT INTO '.$this->table.$fields.'VALUES'.$fields2;
                    $stmt = Db::$db->prepare($sql);

                    foreach ($this->fields as $key => $value)
                        $stmt->bindParam(':'.$key,$$key);

                    extract($this->fields);
                    $stmt->execute();

                    return true;
                }
                catch(\PDOException $e)
                {
                    showSystemError("cant insert" , $e->getMessage());
                }
        }
        else
        {
            try
            {
                $pkVal = $this->fields[$pk];

                $files = $_FILES;

                if(!empty($files['img']['tmp_name']))
                    $this->uploadFiles($files);

                $set = "";
                foreach ($this->fields as $key => $value) {
                    $set .= '`'.$key.'`'. " = :" . $key . " ,";
                }
                $set = rtrim($set,",");
                $sql = 'UPDATE '.$this->table.' SET '.$set.' WHERE '.$pk.'='.$pkVal;
                $stmt = DB::$db->prepare($sql);
                foreach ($this->fields as $key => $value)
                    $stmt->bindParam(':'.$key,$$key);
                extract($this->fields);
                $stmt->execute();

                return true;
            }
            catch(\PDOException $e)
            {
                showSystemError("cant update" , $e->getMessage());
            }
        }
    }

    public function delete()
    {
        try
        {
            $pk     = $this->primary_key;
            $sql    = 'DELETE FROM '.$this->table.' WHERE '.$pk.' = ?';
            $stmt   = Db::$db->prepare($sql);
            $stmt->execute([$this->fields[$pk]]);
        }
        catch(\PDOException $e)
        {
            showSystemError("cant delete" , $e->getMessage());
        }
        return true;
    }

    public static function truncate()
    {
        try
        {
            $obj   = get_called_class();
            $table = (new $obj)->table;
            $sql   = "TRUNCATE TABLE $table";
            $stmt  = Db::$db->prepare($sql);
            $stmt->execute();
        }
        catch(\PDOException $e)
        {
            showSystemError("cant Truncate" , $e->getMessage());
        }
        return true;
    }

    public static function where($first,$cond,$second,$order = "id|ASC")
    {
        try
        {
            $order = explode('|',$order);
            $obj   = get_called_class();
            $obj   = new $obj;
            $sql   = "SELECT * FROM ".$obj->table." WHERE ".$first.$cond."? ORDER BY $order[0] $order[1]";
            $stmt  = Db::$db->prepare($sql);
            $stmt->execute([$second]);
        }
        catch(\PDOException $e)
        {
            showSystemError("cant find any thing" , $e->getMessage());
        }
        return self::convertToObj($stmt);
    }

    public static function search($like,...$col)
    {
        try
        {
            $obj   = get_called_class();
            $obj   = new $obj;
            $like  = '%'.$like.'%';
            $likes = [];
            $qMark = "";
            foreach ($col as $value)
            {
                $qMark  .= $value . " LIKE ? OR ";
                $likes[] = $like;
            }
            $qMark = rtrim($qMark,'OR ');
            $sql   = "SELECT * FROM ".$obj->table." WHERE ".$qMark;
            $stmt  = Db::$db->prepare($sql);
            $stmt->execute($likes);
        }
        catch(\PDOException $e)
        {
            showSystemError("cant search",$e->getMessage());
        }
        return self::convertToObj($stmt);
    }

    public static function findBy($val,$prop,$obj,$order = "id|ASC")
    {
        $order = explode('|',$order);
        try
        {
            $sql  = "SELECT * FROM ".$obj->table." WHERE `".$prop."` = ? ORDER BY $order[0] $order[1]";
            $stmt = Db::$db->prepare($sql);
            $stmt->execute([$val]);
        }
        catch(\PDOException $e)
        {
            showSystemError("cant find any thing" , $e->getMessage());
        }
        return self::convertToObj($stmt);
    }

    public static function convertToObj($res)
    {
        $collect = [];
        while($row = $res->fetch(\PDO::FETCH_ASSOC))
        {
            $object = get_called_class();
            $object = new $object;
            foreach ($row as $key => $value)
            {
                $object->$key = $value;
            }
            $collect[] = $object;
        }
        return $collect;
    }

    protected function validate()
    {
        $validator = new Validator($this->errorMessage);
        $files = $_FILES;
        if(isset($files))
        {
            $names = array_keys($files);
            foreach ($names as $name)
            {
                if(!( empty($fields[$name]) && ! $this->isNew() ))
                {
                    $this->fields[$name] = $files[$name];
                }
                else
                {
                    $this->fields[$name] = $_POST[$name];
                }
            }
        }
        $validation = $validator->validate($this->fields,$this->rules);
        $this->errors = $validation->errors();
        return !$validation->fails();
    }

    public function uploadFiles($files)
    {
        if(isset($files))
        {
            $names = array_keys($files);
            $imgNames = [];
            foreach ($names as $name)
            {
                $rand = rand(145,954454) * rand(5,2) + 3 . '_';
                $imgNames[$name] = $rand . $files[$name]['name'];
                $imgPath = UPLOADDIR . $imgNames[$name];
                try
                {
                    move_uploaded_file($files[$name]["tmp_name"], $imgPath);
                }
                catch(\PDOException $e)
                {
                    showSystemError("cant upload image" , $e->getMessage());
                }
                $this->fields[$name] = $imgNames[$name];
            }
        }
    }

    public function  __get($var)
    {
        if(in_array($var,array_keys($this->fields)))
        {
            return $this->fields[$var];
        }
    }

    public function __set($var,$val)
    {
        if(in_array($var,array_keys($this->fields)))
        {
            if(!in_array($var,$this->guarded) || $this->isNew())
            {
                $this->fields[$var] = $val;
            }
        }
    }

    public static function __callStatic($func,$args)
    {
        $funcname = $func;
        if(substr($funcname , 0 , strlen('findBy')) === 'findBy')
        {
            $prop = strtolower(ltrim($funcname,'findBy'));
            $objname = get_called_class();
            $obj = new $objname;
            if(in_array($prop,array_keys($obj->fields)))
            {
                $args[] = $prop;
                $args[] = $obj;
                return call_user_func_array($objname.'::findBy',$args);
            }
        }
    }

}