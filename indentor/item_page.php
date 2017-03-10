<?php
  session_start();
  require '../connection.php';
  /*if (!isset($_SESSION['user_name'])|| $_SESSION['role']!="indentor") {
    # code...
    header("Location: http://localhost/store/index.php");
    exit();
  }*/
  if (isset($_POST['purpose'])) {
    # code...

    $cur_date=date('Y-m-d');
    $purpose=$_POST['purpose'];
    $user=mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM users WHERE id='".$_SESSION['user_name']."'"));
    //echo mysqli_error($link);
   mysqli_query($link,"INSERT INTO indent(user_id,department,purpose,date) VALUES ('".$_SESSION['user_name']."','".$user['department']."','".$purpose."','".$cur_date."')");
   echo mysqli_error($link);
   $indent=mysqli_fetch_assoc(mysqli_query($link,"SELECT max(id) as id FROM indent"));
   for ($i=0; $i<(count($_POST)-1)/3; $i++) { 
     # code...
    $group=mysqli_fetch_assoc(mysqli_query($link,"SELECT id FROM group_master WHERE group_name='".$_POST['group'.($i+1)]."'"));
   // echo "SELECT id FROM group_master WHERE group_name=".$_POST['group'.($i+1)];
    $item=mysqli_fetch_assoc(mysqli_query($link,"SELECT id FROM item_master WHERE name='".$_POST['name'.($i+1)]."' AND group_id=".$group['id']));
    //echo "SELECT id FROM item_master WHERE name='".$_POST['name'.($i+1)]."' AND group_id=".$group['id'];
    mysqli_query($link,"INSERT INTO indent_item(indent_id,item_id,quantity) VALUES (".$indent['id'].",".$item['id'].",".$_POST['quantity'.($i+1)].")");
    //echo "INSERT INTO indent_item VALUES (".$indent['id'].",".$item['id'].",".$_POST['quantity'.($i+1)].")";
    //echo mysqli_error($link);
   }
   echo "<script>alert('Successful subbmission.'');</script>";
  }
  $group=mysqli_query($link,"SELECT * FROM group_master");

?><html>
<head>
	<title>ITEMS LIST</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="../css/navigate.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
</head>

<body>
  <?php require ('header.html');?>
  <?php require ('sidebar.html');?>
<div style="padding-top: 230px; padding-left: 240px; padding-right:20px;">
<h3 class="text-center" style="padding-bottom: 10px">OUR INVENTORY</h3>
<div class=" well well-lg">
<div class="row">
<form>
<div class="form-group-sm form-inline">

<div class="col-lg-3">
<label for="type">Item Category</label>
<select class="form-control" name="group" id="group" style="width:100%" onchange="create()">
  <option></option>
  <?php while($group_row=mysqli_fetch_assoc($group)){?>
  <option value="<?php echo $group_row['group_name']?>"><?php echo $group_row['group_name']?></option>
  <?php } ?>
</select>
</div>

<div class="col-lg-3" id="items">
<label for="item_name">Item Name</label>
</div>

<div class="col-lg-3">
<label for="quantity">ITEM Quantity</label>
<input type="number" name="quantity" id="quantity" class="form-control" min="0" max="20" style="width:100%" >
</div>

<div class="col-lg-3">
<button type="button" name="add" class="form-control btn btn-primary" style="width: 100%;margin-top: 27px" onclick="add_row()" >Add</button>
</div>
</div>
</form>
</div>
</div>


<div class="text-center"><h3>List of Items Taken:</h3></div>
<div class="table-responsive">
<form action="item_page.php" method="POST" id="myForm">
<table data-spy="scroll" class="table table-striped table-bordered table-condensed">
	<thead><tr><th>Sr. No.</th><th>Item Name</th><th>Item Sub-Category</th><th>Quantity</th><th></th></tr></thead>
	<tbody id="table">
	</tbody>
</table>
</form>
<input type="button" name="submit" class="btn btn-primary" value="SUBMIT" onclick="submit()">
</div>
</div>
</div>
<script type="text/javascript">
   last=null;
   curr_item_no=null;
   function create(){
    console.log("change");
        if (last!=null) {
          var parent_to_be_del=document.getElementById("items");
          var to_be_del=document.getElementById("select_item");
          parent_to_be_del.removeChild(to_be_del);
        }
        var select_group=document.getElementById("group");
        var to_be_inserted=document.getElementById("items");
        <?php
          $group=mysqli_query($link,"SELECT * FROM group_master");
         while($group_row=mysqli_fetch_assoc($group)){?>
          console.log("first while");
          if(select_group.value==="<?php echo $group_row['group_name'];?>"){
            console.log("if");
         select_name=document.createElement("select");
         select_name.setAttribute("class","form-control");
         select_name.setAttribute("name","select_item");
         select_name.setAttribute("id","select_item");
         select_name.setAttribute("style","width: 100%");
         var blank=document.createElement("option");
          blank.innerHTML="";
          select_name.appendChild(blank);
          <?php 
          $items=mysqli_query($link,"SELECT * from item_master WHERE group_id=".$group_row['id']);

          while($item=mysqli_fetch_assoc($items)){?>
            console.log("second while");
            select_option=document.createElement("option");
            select_option.innerHTML="<?php echo $item['name'];?>";
            select_name.appendChild(select_option);
            select_option.value="<?php echo $item['name'];?>";
         <?php }?>
          to_be_inserted.appendChild(select_name);
          
          last=select_name;
          
          }
         <?php } ?>
     } 
   count=1;
   arry=[];
   function add_row(){
      console.log("add");
      if(last!=null){
        var item=document.getElementById("select_item").value;
        if(item===""){
          alert('All fields required!');
          return;
        }
      }
      var group=document.getElementById("group").value;
      var quantity=document.getElementById("quantity").value;
      if ( group==="" || quantity==="") {
        alert('All fields required!');
        return;
      }
      if (quantity==="0" ) {
        alert('Item quantity cannot be zero!');
        return;
      }
      var temp=item+group;
      for(i=0;i<arry.length;i++){
          if (temp===arry[i]) {
            break;
          }
      }
      if (i===arry.length) {
        arry[arry.length]=temp;
      }else{
        alert('Same item cannot be added twice.');
        return;
      }
      var table=document.getElementById("table");
      var row=document.createElement("tr");
      var data=document.createElement("td");
      data.innerHTML=count;
      row.appendChild(data);


      data=document.createElement("td");
      data.innerHTML=item;
      var input=document.createElement("input");
      input.setAttribute("name","name"+count);
      input.setAttribute("type","text");
      input.setAttribute("value",item);
      input.style.width="10%";
      input.style.visibility="hidden";
      data.appendChild(input);
      row.appendChild(data);


      data=document.createElement("td");
      data.innerHTML=group;
      var input=document.createElement("input");
      input.setAttribute("name","group"+count);
      input.setAttribute("type","text");
      input.setAttribute("value",group);
      input.style.width="10%";
      input.style.visibility="hidden";
      data.appendChild(input);
      row.appendChild(data);


      data=document.createElement("td");
      data.innerHTML=quantity;
      var input=document.createElement("input");
      input.setAttribute("name","quantity"+count);
      input.setAttribute("type","text");
      input.setAttribute("value",quantity);
      input.style.width="10%";
      input.style.visibility="hidden";
      data.appendChild(input);
      row.appendChild(data);

      data=document.createElement("td");
      var rem=document.createElement("button");
      rem.setAttribute("onclick","rm(this,'"+temp+"')");
      var del=document.createElement("span");
      del.setAttribute("class","glyphicon glyphicon-remove");
      rem.appendChild(del);
      data.appendChild(rem);
      row.appendChild(data);



      table.appendChild(row);
      count++;
  }

  function submit(){
    if(arry.length<1){
      alert('There must be atleast one item.');
      return;
    }
    var form=document.getElementById("myForm");
    var purpose=document.getElementById("purpose");
    if (purpose==null) {
    var purpose=document.createElement("textarea");
    purpose.setAttribute("name","purpose");
    purpose.setAttribute("placeholder","Purpose for indent");
    purpose.setAttribute("id","purpose");
    purpose.setAttribute("class","form-control");
    purpose.required;
    form.appendChild(purpose);
    }else if (purpose.value!=""){
      form.submit();
    }
  }
  function rm(btn,temp){
    var tr=btn.parentNode.parentNode;
    tr.parentNode.parentNode.deleteRow(tr.rowIndex);
    var index=arry.indexOf(temp);
    console.log(index);
    if (index>-1) {
      arry.splice(index,1);
    }
  }
 </script>
 <script src="http://www.w3schools.com/lib/w3.js"></script>
</body>
</html>