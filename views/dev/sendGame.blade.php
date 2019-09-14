@extends('res.master')
@section('title')
    ارسال بازی
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ url('dev/send') }}" enctype="multipart/form-data">
                                @method(PUT)
                                <div class="form-group">
                                    <label for="email"> نام بازی:<b class="text-danger"> {{ $errors['name'] ?? '' }}</b></label>
                                    <input class="form-control" type="text" name="name" value="{{ $product->name ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label for="email"> فایل فلش:<b class="text-danger"> {{ $errors['img'] ?? '' }}</b></label>
                                    <input class="form-control" type="file" name="file" id="">
                                </div>
                                <button type="submit" name="add_product" class="btn btn-primary">ارسال بازی</button>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-bold"></h5>

                            <p class="card-text">
                            <p class="dropdown-divider"></p>
                            <table class="table table-bordered table table-hover">
                                @forelse($games as $game)
                                    <tr>
                                        <td class="col-12">{{ $game->name }}</td>
                                        <td><a href="{{ url('dev/delete/'.$game->id) }}">حذف</a></td>
                                    </tr>
                                @empty
                                    <p>هیچ بازی یافت نشد</p>
                                @endforelse
                            </table>
                            </p>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection