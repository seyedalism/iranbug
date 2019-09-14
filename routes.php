<?php use App\Core\Router;

Router::get("/" , "HomeController@home");

//user
Router::get("login" , "UserController@showLogin");

Router::get("logout" , "UserController@logout");

Router::get("register" , "UserController@show");

Router::put("register" , "UserController@register");

Router::post("login" , "UserController@login");

Router::get("edit" , "UserController@takmil");

Router::put("edit" , "UserController@takmiler");

Router::get("checkout" , "BasketController@checkout");

Router::get("reply" , "BasketController@reply");

Router::post("pcode/{id}" , "HomeController@pcode");

/*
 *
 *  _______________ admin ________________
 *
 */

// admin login
Router::get("admin/login" , "AdminController@show");

Router::get("admin/home" , "AdminController@home");

Router::post("admin/login" , "AdminController@login");

Router::get("admin/logout" , "AdminController@logout");

//advertise
Router::get('admin/advertise','AdvertiseController@show');
Router::get('admin/advertise/delete/{id}','AdvertiseController@delete');
Router::put('admin/advertise','AdvertiseController@add');

Router::get('admin/advertise/zirnevis','AdvertiseController@zirnevisManage');
Router::get('admin/advertise/zirnevis/delete/{id}','AdvertiseController@zirnevisDelete');
Router::put('admin/advertise/zirnevis','AdvertiseController@zirnevisAdd');

Router::get('admin/advertise/dynamic','AdvertiseController@dynamicManage');
Router::get('admin/advertise/dynamic/delete/{id}','AdvertiseController@dynamicDelete');
Router::put('admin/advertise/dynamic','AdvertiseController@dynamicAdd');

// table
Router::put("admin/add/sit" , "OrderController@addSit");
Router::get("admin/reserved" , "OrderController@showReserved");

//admin detail res
Router::get("admin/detail-Res","ResController@Adetail");
Router::post("admin/detail-Res","ResController@AeditDetail");

//manage pays
Router::get('admin/manage-pays' , 'PayController@show');
Router::get('admin/remove-pay/{id}' , 'PayController@removePay');
Router::get('admin/detail-pay/{id}' , 'PayController@detailPay');

//admin manage users
Router::get("admin/manage-users" , "UserController@showUsers");
Router::get("admin/manage-special-users" , "UserController@showSpecials");
Router::get("admin/manage-dev-users" , "UserController@showDevs");
Router::get("admin/remove-user/{type}/{id}" , "UserController@removeUser");
Router::get("admin/show-user/{id}" , "UserController@showUser");
Router::get("admin/promote-to-special/{id}" , "UserController@promoteToS");
Router::get("admin/promote-to-dev/{id}" , "UserController@promoteToD");

// about us and benefits
Router::post("admin/upload" , 'OptionController@upload');
Router::get("admin/about-us" , 'OptionController@aboutUs');
Router::post("admin/about-us" , 'OptionController@addAbout');
Router::get("admin/benefits" , 'OptionController@benefits');
Router::post("admin/benefits" , 'OptionController@addBenefits');

// category
Router::get("admin/category" , "CategoryController@show");
Router::put("admin/category" , "CategoryController@add");
Router::patch("admin/category" , "CategoryController@update");
Router::get("admin/category/delete/{id}" , "CategoryController@mainDelete");
Router::get("admin/category/sub/delete/{id}" , "CategoryController@subDelete");

// product
Router::get("admin/add-product" , 'ProductsController@show');
Router::put("admin/add-product" , 'ProductsController@add');
Router::get("admin/show-products" , "ProductsController@manageProducts");

Router::get("admin/remove-product/{id}" , "ProductsController@deleteProduct");
Router::get("admin/edit-product/{id}" , "ProductsController@show");
Router::patch("admin/edit-product/{id}" , "ProductsController@update");

//slides
Router::get("admin/slides/delete/{id}" , "SlidesController@delete");
Router::get("admin/slides" , "SlidesController@show");
Router::put("admin/slides" , "SlidesController@add");

// games
Router::get("admin/games","GameBoxController@manage");
Router::put("admin/games","GameBoxController@add");
Router::get("admin/games/{id}","GameBoxController@delete");
/*
 *
 *  ___________ restaurant_____________
 *
 */
//table
Router::get("restaurant/sit/setting" , "ResController@sitSetting");

Router::get("restaurant/rm-sit/{id}" , "ResController@rmvSit");

Router::put("restaurant/add/sit" , "ResController@addSit");

Router::get("restaurant/reserved" , "ResController@showReserved");

Router::get("admin/sit/setting" , "OrderController@sitSetting");

Router::get("admin/rm-sit/{id}" , "OrderController@rmvSit");

//detail res
Router::get("restaurant/detail-Res" , "ResController@detail");

Router::post("restaurant/detail-Res" , "ResController@editDetail");

//special
Router::get("restaurant/event" , 'ResController@specialShow');

Router::put("restaurant/add-event" , 'ResController@addEvent');

Router::put("restaurant/add-img-event" , 'ResController@addImgEv');

Router::delete("restaurant/rmv-img-event/{id}" , 'ResController@rmvImgEv');

// login logout
Router::get("restaurant/login" , "ResController@loginPage");

Router::get("restaurant/dashboard" , "ResController@dashboard");

Router::get("restaurant/logout" , "ResController@logout");

Router::post("restaurant/login" , "ResController@login");

//product
Router::post("restaurant/down" , "RestaurantsController@ajax");

Router::get("restaurant/remove-product/{id}" , "ResController@deleteProduct");

Router::get("restaurant/edit-product/{id}" , "ResController@show");

Router::patch("restaurant/edit-product/{id}" , "ResController@update");

Router::get("restaurant/show-products" , "ResController@manageProducts");

Router::get("restaurant/add-product" , "ResController@show");

Router::put("restaurant/add-product" , "ResController@addProduct");

//slide
Router::get("restaurant/slides/delete/{id}" , "ResController@slideDelete");

Router::get("restaurant/slides" , "ResController@slideShow");

Router::put("restaurant/slides" , "ResController@slideAdd");

////cat
Router::get("restaurant/category" , "ResController@Catshow");

Router::put("restaurant/category" , "ResController@Catadd");

Router::patch("restaurant/category" , "ResController@Catupdate");

Router::get("restaurant/category/delete/{id}" , "ResController@CatmainDelete");

Router::get("restaurant/category/sub/delete/{id}" , "ResController@CatsubDelete");

/*
 * ________ user ________
 */
//order
Router::get("order" , 'OrderController@show');

Router::post("order/down/{id}" , 'OrderController@ajax');

//basket
Router::get("basket" , 'BasketController@show');

Router::get("add-to-basket/{id}" , 'BasketController@add');

Router::get("remove-from-basket/{id}" , 'BasketController@remove');

Router::get("checkout" , 'BasketController@checkout');

Router::get("reply" , 'BasketController@reply');

Router::get("status" , 'BasketController@status');

//foods
Router::get("foods/{?page}" , 'FoodController@showAll');

Router::get("food/{id}/{?alert}" , "FoodController@show");

//options
//Router::get("about-us",'HomeController@aboutUs');

Router::get("benefits" , 'HomeController@benefits');
Router::get("contact-us" , 'HomeController@contactUs');

//reserve
Router::get("reserve/{?id}" , 'ReserveController@home');

Router::post('reserve/{?id}' , 'ReserveController@add');

//games
Router::get("games-page" , "GamesController@gamesPage");
Router::get("games/{id}" , "GamesController@show");

Router::get('advertise/{id}','GamesController@redirectToAd');

//restaurants
Router::get("restaurant/restaurantsPage" , function () {
	return view('res.restaurantsPage');
});

Router::get("restaurants/{id}" , 'RestaurantsController@show');

/*
 *  ____________ developers ___________
 */

Router::get('dev/login' , 'DevController@show');

Router::get('dev/dashboard' , 'DevController@home');

Router::post("dev/login" , "DevController@login");

Router::get("dev/send" , "DevController@manageGame");

Router::put("dev/send" , "DevController@send");

Router::get("dev/delete/{id}" , "DevController@delete");

//Router::get("g/{name}" , "GameController@generate");
