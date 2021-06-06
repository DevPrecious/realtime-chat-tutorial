<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0 auto;
  max-width: 800px;
  padding: 0 20px;
}

.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
}

.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
</style>
</head>
<body>

<h2>Chat Messages</h2>

<div id="getmsg">


</div>

<form action="" method="POST">
<div class="form-group">
<textarea name="message" id="msg" class="form-control"></textarea>
<span id="msg_err"></span>
</div>
<div class="form-group pt-3">
    <button type="submit" id="send" class="btn btn-success">Send</button>
</div>

</form>


<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){

        setInterval(function(){
            showmsg();
        }, 5000);
        
        showmsg();

        function showmsg(){
            $.ajax({
                type: "GET",
                url: '/getmsg',
                async: true,
                dataType: 'JSON',
                success: function(data){
                    var html = "";
                    for(i=0; i<data.length; i++){
                        html += 
                            data[i].username + 
                            "<p>" + data[i].message + "</p>" +
                            "<span class='time-right'>"+ data[i].created_at +"</span></div>";
                    }
                    $("#getmsg").html(html);
                },
                error: function(err)
                {
                    console.log(err);
                }
            });
        }

        $("#send").on('click', function(e){
            e.preventDefault();
            var msg = $("#msg").val();
            $.ajax({
                type: "POST",
                url: '/chat',
                dataType: 'JSON',
                data: {message: msg},
                success: function(data){
                    console.log('sent');
                    showmsg();
                    $("#msg").val("");
                },
                error: function(err){
                    $("#msg_err").text(err.responseJSON.messages.message);
                    $("#msg_err").addClass('text-danger');
                }
            });
        });
    });

</script>
</body>
</html>
