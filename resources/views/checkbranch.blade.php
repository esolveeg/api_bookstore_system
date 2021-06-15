<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
           
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            

            <div class="content">
                

                <div class="links">
                    <form  method="post" action="{{url('/checkbranch')}}">
							  @csrf
							  <div class="controls">
								  <div class="row"> 
									  <div class="col-md-6">
										  <div class="form-group">
											<select name="branch">
                                                @foreach ($branches as $branch)
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
										  </div>
									  </div>
									  <div class="col-md-12">
										  <input type="submit" value="submit" class="main-btn btn-3 color-fff radius-50px bg-orange color-orange-hvr">
									  </div>
								  </div>
							  </div>
						  </form>    
                </div>
            </div>
        </div>
    </body>
</html>
