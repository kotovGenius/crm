
@extends("layouts.admin")

@section("style")
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

@endsection
@section("content")
<div class="container_a" style="

width: 900px;
padding-left:40px;

"

>

    <div class="add">
        <h1>Add Employee</h1>
        <a href="/admin/panel/create"><button class="uk-button uk-button-secondary">Add</button></a>
    </div>

    <table class = "table table-bordered" id = "employee-table" width="1600px">

                <thead>
                        <tr>

                            <th>Photo</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Date of employment</th>
                            <th>Phone number</th>
                            <th>Email</th>
                            <th>Salary</th>
                            <th>Action</th>

                        </tr>



                </thead>


    </table>

    <div id="modal-del" uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <p id = "warning"></p>
            <p class="uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close cancel" type="button">Cansel</button>
                <button class="uk-button uk-button-primary uk-modal-close remove" type="button" id="remove_employee">Remove</button>
            </p>
        </div>
    </div>

</div>

@endsection

@section("script")
    <script src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>


    <script>
        feather.replace()
    </script>
    <script>
        $(document).ready(function () {


            let promise = new Promise((resolve, reject) => {

                $("#employee-table").DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{route("admin.panel.getemployee")}}",
                    columns: [
                        {data: "photo", name: "photo"},
                        {data: "full_name", name: "full_name"},
                        {data: "position_id", name: "position_id"},
                        {data: "entry_date", name: "entry_date"},
                        {data: "telephone_number", name: "telephone_number"},
                        {data: "email", name: "email"},
                        {data: "salary", name: "salary"},
                        {data: "action",name:"action"},
                    ],
                    orderClasses: false


                })
                resolve("result");

            })

            let employee_id;
            let flag = false;

            promise.then((result) => {


                $(".container_a table.table").on("click", function (e) {

                    if ($(e.target).attr("action") === "edit") {
                        window.location.href = "{{asset("admin/panel/edit/")}}/"+ $(e.target).attr("value");
                    } else if ($(e.target).attr("action") === "delete"){
                        $("#modal-del #warning").text("Are you sure you want to remove employee " + $(e.target).attr("full-name") );
                        employee_id = $(e.target).attr("value");
                        flag = true;
                    }

                });




            });


            $("#modal-del .cancel").click(function (){
                flag = false;
            });
            $("#modal-del .remove").click(function (){
                if(employee_id && flag){
                    deleteEmployee(employee_id)
                }

            });


            async function deleteEmployee(employee_id){
                let response = await fetch("/admin/panel/delete",{
                    method:"POST",
                    headers:{
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body:JSON.stringify({

                        id:employee_id,
                        '_token':"{{csrf_token()}}"
                    })
                });

                let result = await response.json();

                console.log(result.message);

            }





        })







    </script>

@endsection


