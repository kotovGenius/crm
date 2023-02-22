@extends("layouts.admin")
@section('style')
    <style>

        .head_container .head_suppose .active:hover{
            background-color: lightskyblue;
            cursor: pointer;
        }

    </style>
@endsection
@section("content")

    @if($errors->has('success'))

            <div class="uk-alert-warning" uk-alert>

                <a class="uk-alert-close" uk-close></a>
                <p>{{$errors->first('success')}}</p>
            </div>


    @endif
    <div class="js-upload uk-placeholder uk-text-center" style="max-width: 600px;margin-left: 50px;">
        <div class="image_container" >
            <div class="image">
                <img src="@if(!empty($employee)){{asset($employee->photo)}} @else {{asset("img/default_photo.png")}}@endif " alt="" style="width: 120px; height: 120px; ">
            </div>
        </div>
        <div class="js-upload" uk-form-custom>
            <input type="file" multiple name="img" id="load_img">
            <button class="uk-button uk-button-default" tabindex="-1">Select</button>
        </div>
    </div>
    <div class="uk-alert-danger" uk-alert id = "img_alert" style="display: none">
        <a class="uk-alert-close" uk-close></a>
        <p></p>
    </div>
    <form method="POST" style="
    max-width: 600px;
    margin: 50px;
    "  action="{{route("employee.edit")}}" enctype="multipart/form-data" >


        <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>

        <input type="text" name="id" style="display: none" value="@if(!empty($employee)){{$employee->id}}@endif">
        @csrf
        @if($errors->has('name'))
            <div class="uk-alert-danger" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <p>{{$errors->first('name')}}</p>
            </div>
        @endif

        <input type="text" hidden id="current_img" name="img">
        <label for="name">Full Name</label>
        <input class="uk-input" type="text" placeholder="Full Name" aria-label="Full Name" name="name" value="@if(!empty($employee)){{$employee->full_name}}@endif">

        @if($errors->has('phone'))
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>{{$errors->first('phone')}}</p>
            </div>
        @endif

        <label for="phone">Phone</label>
        <input class="uk-input" type="text" placeholder="Phone" aria-label="phone" name="phone" value="@if(!empty($employee)){{$employee->telephone_number}}@endif">

        @if($errors->has('email'))
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>{{$errors->first('email')}}</p>
            </div>
        @endif
        <label for="email">Email</label>
        <input class="uk-input" type="email" placeholder="Email" aria-label="email" name="email" value="@if(!empty($employee)){{$employee->email}}@endif">
        <label for="email">Position</label>
        <select class="uk-select" aria-label="Select" name="position">
            <option value="@if(!empty($employee)){{$employee->position_id}}@endif">@if(!empty($current_position)){{$current_position->name}}@endif</option>
            @foreach($positions as $position)
                <option value="{{$position->id}}">{{$position->name}}</option>
            @endforeach
        </select>
        <label for="salary">Salary,$</label>
        <input class="uk-input" type="text" placeholder="Salary" aria-label="Salary" name="salary" value="@if(!empty($employee)){{$employee->salary}}@endif">
        <div class="head_container" style="position: relative">
            <label for="head">Head</label>
            <input class="uk-input" type="text" placeholder="Head" aria-label="Head" name="head" value="@if(!empty($manager)){{$manager->full_name}} @endif" id = "head">
            <input class="uk-input" type="text" placeholder="Head" aria-label="Head" name="head_id" value="@if(!empty($manager)){{$manager->id}}@endif" id = "head_id" style="display: none">
            <div class="head_suppose uk-card uk-card-body uk-card-default"
                 style="
                    position: absolute;
                    padding: 10px;
                    display: none;
                    top: 80px;
                 ">

            </div>
        </div>
        @if($errors->has('date'))
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>{{$errors->first('date')}}</p>
            </div>
        @endif
        <label for="date">Date of employment</label>
        <input class="uk-input" type="text" placeholder="date" aria-label="date" name="date" value="@if(!empty($employee)){{$employee->entry_date}}@endif" id = "date">
        <div class="button_container" style="margin-top:20px">
            <button class="uk-button uk-button-default" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" type="submit">Save</button>
        </div>
        @if(!empty($employee))
        <div class="container_cu" style="display: flex ">
            <div class="system_1" >
                <div class="created_at">Created at: {{$employee->created_at}}</div>
                <div class="updated_at">Updated at: {{$employee->updated_at}}</div>
            </div>
            <div class="system_2" >
                <div class="admin_id_create">Admin created id:{{$employee->admin_created_id}}</div>
                <div class="admin_id_update">Admin updated id:{{$employee->admin_updated_id}}</div>
            </div>
        </div>
        @endif

    </form>
@endsection

@section("script")





    <script>

        var bar = document.getElementById('js-progressbar');


        $("#head").blur(function (){
            $(".head_container .head_suppose").css('display','none');
        })

        $("#load_img").change(function (e){
            let formData = new FormData();

            formData.append('img',document.getElementById("load_img").files[0]);
            setImg(formData,'temp');
        });

        async function setImg(formData,parameter){
            formData.append('parameter' , parameter)
            formData.append('_token',"{{csrf_token()}}");
            let response = await fetch('/admin/panel/load/img',{
                method:"POST",
                body:formData,
            })

            let result = await response.json();
            if(result.success){
               $(".image_container .image img").attr("src",result.path);
               $("#current_img").val(result.path);

               if(result.errors){

                   let errors = "";
                   result.errors.img.forEach(el=>{

                     errors += el +"<br>";

                   });

                   $('#img_alert p').html(errors);
                   $('#img_alert').css('display','block');
               } else {
                   $('#img_alert').css('display','none');
               }
            }
        }

        $("#date").datepicker({
            dateFormat:'dd.M.yy',
            monthNamesShort: [ '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12' ],
            changeMonth:true,
            changeYear:true,

        });

        let flag = true;
        $("#head").keypress(function (){

            if(flag){
                flag = false;
                let name = $("#head").val();
                getNames(name);
            }

            $(".head_container .head_suppose").css('display','block')

            let promise = new Promise((resolve,reject)=>{
                setTimeout(function (){
                    flag = true;
                },300)
            })

            promise.then(val => {
                getNames(name);
            })





        });

        $(".head_container .head_suppose").on("click",function (e){
            $("#head").val($(e.target).text());
            $("#head_id").val($(e.target).attr('id'));
            $(".head_container .head_suppose").css('display','none')
        });


        async function getNames(name){
            let text = $(".head_container .head_suppose").html("");
            let response = await fetch(`/admin/panel/edit/names/get?name=${encodeURIComponent(name)}`,{
                method:"GET"
            })
            let result = await response.json();


            result.names.forEach(e => {
                text = $(".head_container .head_suppose").html();
                $(".head_container .head_suppose").html(text + `<div id="${e.id}" class = "active">${e.full_name}</div>`)
            });
            console.log(result.names);
        }

    </script>




















    </script>



@endsection
