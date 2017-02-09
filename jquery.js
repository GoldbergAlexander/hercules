
$(document).ajaxError(function(event,jqxhr,settings,thrownError){
	var msg = "Sorry, an error has occurred: ";
	$("#status").html(msg + jqxhr.status + " " + thrownError);

});
$(document).ajaxSuccess(function(event, jqxhr,settings){
	$("#status").empty();
});

$(document).on('click','#home', function(){
	$("#main").load("/home/home.php");
});
$(document).on('click','#entry',function(){
	$("#main").load("/entry/entry.php");
});
$(document).on("click","#display",function(){
	$("#main").load("/display/display.php");
});
$(document).on("click","#login", function(){
	$("#main").load("/login/login.php");
});
$(document).on("click","#register",function(){
	$("#main").load("register.php");
});

$(document).on("click","#managment",function(){
	$("#main").load("/management/managment.php");
});	
	
$(document).on("click","#logout",function(){
	$.post("/login/logout.php",function(data){
		$("#nav").load("nav.php");
		$("#main").html(data);
	});
});
$(document).on("click","#account",function(){
	$("#main").load("/account/account.php");
});
$(document).on("submit","#loginform",function(e) {
	var url = "/login/loginController.php";
	$.ajax({
		type:"POST",
		url:url,
		data:$("#loginform").serialize(),
		}).done(function(data){
			if(data == "valid"){
					$("#main").load("/home/home.php");
					$("#nav").load("nav.php");
				}else{
					$(".error").html(data);
					$(".loginsubmit").blur();
				}
		});
		e.preventDefault();
});
$(document).on("submit","#accountpasswordchangeform",function(e) {
	var url = "/account/changePassword.php";
	$.ajax({
		type: "POST",
		url: url,
		data: $("#accountpasswordchangeform").serialize(),
	}).done(function(data){
		$(".accountpasswordstrength").html(data);
		$(".accountpasswordchangeupdate").blur();
	});
	e.preventDefault();
});

$(document).on("submit","#entryform",function(e) {
	var url = "/entry/entryStore.php";
	$.ajax({
		type:"POST",
		url: url,
		data:$("#entryform").serialize(),
	}).done(function(data){
		$("#main").load("/entry/entryReview.php");
	});
	e.preventDefault();
});

$(document).on("submit","#submitdata",function(e) {
	$("#main").load("/entry/entryInsert.php");
	e.preventDefault();
});

$(document).on("submit","#backentry",function(e) {
	$("#main").load("/entry/entry.php");
	e.preventDefault();
});

$(document).on("submit","#cancelsubmit",function(e) {
	var url = "/entry/entryClear.php";
	$.ajax({
		type:"POST",
		url: url,
	}).done(function(data){
		$("#main").load("/entry/entry.php");
	});
	e.preventDefault();

});

//Disable enter here
$(document).on("keypress","input",function(e) {
	if((e.keyCode == 13 || e.keyCode == 3) && e.target.type !== 'submit'){
		e.preventDefault();
	}
});

//Display 
$(document).on("submit","#chartform",function(e) {
	var url = "/display/data.php";
	try{
	var chart = FusionCharts('Revenue Chart');
	chart.dispose();
	}catch(e){

	}
	var submitData = $("#chartform").serialize()
	$.ajax({
		type:"POST",
		url: url,
		data: submitData,
	}).done(function(data){
		$("#chart-1").html(data);
	});
	e.preventDefault();

});

//Admin Function
$(document).on("submit","#userform",function(e) {
	var form = $(this).closest("form");
	var url = "/management/user/userController.php";
	var btn = $(this).find("input[type=submit]:focus");
	//if password needs to be generated
	var pass;
	if(btn.attr("name") === "password" || btn.attr("name") === "create"){
		pass = Math.random().toString(36).slice(2);
		$("input[name=password]", this.form).val(pass);
	}
	var dat = $(form).serialize();
	dat += "&submit=" + btn.attr("name");
	$.ajax({
		type:"POST",
		url: url,
		data:dat,

	}).done(function(data){	
		$("#main").load("/management/managment.php",function(d){
			$(".password").html(data);
		});
	});
	e.preventDefault();
});

$(document).on("submit","#locationform",function(e) {
	var form = $(this).closest("form");	
	var url = "/management/location/locationController.php";
	var btn = $(this).find("input[type=submit]:focus");
	var dat = $(form).serialize();
	dat += "&submit=" + btn.attr("name");
	$.ajax({
		type:"POST",
		url: url,
		data:dat,
	}).done(function(data){	
		$("#main").load("/management/managment.php",function(d){
			$(".password").html(data);
		});	
	});
	e.preventDefault();
});

$(document).on("submit","#groupform",function(e) {
	var form = $(this).closest("form");	
	var url = "/management/group/groupController.php";
	var btn = $(this).find("input[type=submit]:focus");
	var dat = $(form).serialize();
	dat += "&submit=" + btn.attr("name");
	$.ajax({
		type:"POST",
		url: url,
		data:dat,
	}).done(function(data){	
		$("#main").load("/management/managment.php",function(d){
			$(".password").html(data);
		});
	});
	e.preventDefault();
});



