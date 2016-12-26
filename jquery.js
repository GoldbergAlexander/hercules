
$(document).ajaxError(function(event,jqxhr,settings,thrownError){
	var msg = "Sorry, an error has occurred: ";
	$("#status").html(msg + jqxhr.status + " " + thrownError);

});
$(document).ajaxSuccess(function(event, jqxhr,settings){
	$("#status").empty();
});

$(document).on('click','#home', function(){
	$("#main").load("home.php");
});
$(document).on('click','#entry',function(){
	$("#main").load("entry.php");
});
$(document).on("click","#display",function(){
	$("#main").load("display.php");
});
$(document).on("click","#login", function(){
	$("#main").load("login.php");
});
$(document).on("click","#register",function(){
	$("#main").load("register.php");
});

$(document).on("click","#managment",function(){
	$("#main").load("managment.php");
});	
	
$(document).on("click","#logout",function(){
	$.post("logout.php",function(data){
		$("#nav").load("nav.php");
		$("#main").html(data);
	});
});
$(document).on("click","#account",function(){
	$("#main").load("account.php");
});
$(document).on("submit","#loginform",function(e) {
	var url = "/login_internal.php";
	$.ajax({
		type:"POST",
		url:url,
		data:$("#loginform").serialize(),
		}).done(function(data){
			if(data == "valid"){
					$("#main").load("home.php");
					$("#nav").load("nav.php");
				}else{
					$(".error").html(data);
					$(".loginsubmit").blur();
				}
		});
		e.preventDefault();
});
$(document).on("submit","#accountpasswordchangeform",function(e) {
	var url = "/passwordchange_internal.php";
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
	var url = "/entrystore.php";	
	$.ajax({
		type:"POST",
		url: url,
		data:$("#entryform").serialize(),
		

	}).done(function(data){
		$("#main").load("/entryreview.php");
	});
	e.preventDefault();
});

$(document).on("submit","#submitdata",function(e) {
	$("#main").load("/dailyrevenueentry.php");
	e.preventDefault();
});

$(document).on("submit","#backentry",function(e) {
	$("#main").load("/entry.php");
	e.preventDefault();
});

$(document).on("submit","#cancelsubmit",function(e) {
	var url = "/cancel.php";	
	$.ajax({
		type:"POST",
		url: url,
	}).done(function(data){
		$("#main").load("/entry.php");
	});
	e.preventDefault();

});

//Disable enter here
$(document).on("keypress","input",function(e) {
	if((e.keyCode == 13 || e.keyCode == 3) && e.target.type !== 'submit'){
		e.preventDefault();
	}
});


//Admin Function
$(document).on("submit","#userform",function(e) {
	var form = $(this).closest("form");
	var url = "/usercontroler.php";	
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
		$("#main").load("/managment.php",function(d){
			$(".password").html(data);
		});	
	});
	e.preventDefault();
});

$(document).on("submit","#locationform",function(e) {
	var form = $(this).closest("form");	
	var url = "/locationcontroler.php";	
	var btn = $(this).find("input[type=submit]:focus");
	var dat = $(form).serialize();
	dat += "&submit=" + btn.attr("name");	
	$.ajax({
		type:"POST",
		url: url,
		data:dat,
	}).done(function(data){	
		$("#main").load("/managment.php",function(d){
			$(".password").html(data);
		});	
	});
	e.preventDefault();
});

$(document).on("submit","#groupform",function(e) {
	var form = $(this).closest("form");	
	var url = "/groupcontroler.php";	
	var btn = $(this).find("input[type=submit]:focus");
	var dat = $(form).serialize();
	dat += "&submit=" + btn.attr("name");	
	$.ajax({
		type:"POST",
		url: url,
		data:dat,
	}).done(function(data){	
		$("#main").load("/managment.php",function(d){
			$(".password").html(data);
		});	
	});
	e.preventDefault();
});



