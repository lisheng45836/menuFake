/*
* Filename: js.js
* Created: Lisheng Liu
*/

/**
* Change displayed restaurants between delivery and pick up.
* list.html
*/
$(document).on('change','.listType',function(){

	if($(this).prop('checked')==true){
		var cartType = $(this).val();
		$("#cartType").val(cartType);
		$(".main").empty().html(getRestaurant());
	}
});

/**
* getRestaurant function use ajax to get restaurants data from database and append to webpage.
*/
function getRestaurant(){

	var url = $("#target").attr("action");
		
		$.ajax({
			type: 'GET',
			url: url,
			data: $("#target").serialize(),
			success:function(data){
				var restaurant = JSON.parse(data);
				for(i=0;i<restaurant.length;i++){
					var title = restaurant[i].title;
					var urlTitle = title.replace(/_/g," ");		//replace '_' with space in any title.
					var openTime = restaurant[i].openTime.replace(/(.*)\D\d+/, '$1');	// remove sec from time format
					var closeTime =restaurant[i].closeTime.replace(/(.*)\D\d+/, '$1');	//
					// generated html with restaurant data
					var content =	'<tr>'+	
									'<td>'+
										'<div class="row">'+
											'<div class="col-md-4">' +
								  				'<img class="img-rounded img" src="'+restaurant[i].image_path+'" width="120px">'+
								  			'</div>'+

								 			'<div class="col-md-8">'+
												'<h3>'+urlTitle+'</h3>'+
												'<span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span> '+
												'<span class="tags">'+restaurant[i].cuisineName + '</span>'+
												'<span class="glyphicon glyphicon-time" aria-hidden="true"></span> '+
												'<span >'+openTime+'</span> - '+
												'<span class="tags">'+closeTime+ '</span>'+
												'<span>Min order </sapn>'+
												'<span>$'+restaurant[i].minOrder+'</span> <br>'+
												'<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>'+
												'<span class="tags">'+restaurant[i].address+'</span> <br>'+
												'<a class="btn btn-default pull-right" href="/restaurants/'+title+'/menus" role="button">See Menu</a>'+
											'</div>'+
										'</div>'+
									'</td>'+
									'</tr>';
						
					$(".main").append(content);	// append into .main, list.html
				}
			}
		});
}

/**
* display restaurants by cuisine type, if tag "all" be clicked, set other to false.
* list.html
*/
$(document).on('change','#all',function(){

	if($("#all").prop('checked')==true){
		$(".cuisineNames").not(this).prop('checked', false); 
		$(".main").empty().html(getRestaurant());
	}

});

/**
* display restaurants by cuisine type.
*
*/
$(document).on('change','.cuisineNames',function(){

	if($(this).prop('checked')==true){
		$("#all").prop('checked', false);
		//$("#target").submit();
		$(".main").empty().html(getRestaurant());
	}else{
		$(".main").empty().html(getRestaurant());
	}

});


$(document).ready(function(){
	//localStorage.clear();

	/********* Start of order cart functions *********/

	/**
	* get data from html form, and save into list array.
	*/
	var list = []; // list array, contains order list
	load();	// call load()

	$(".foods").submit(function(e){
		e.preventDefault();
		var foodData = $(this).serializeArray();
		var food = {};
		
		for(i=0;i<foodData.length;i++){		// loop thought the data and push into food array(object).
			food[foodData[i].name]=foodData[i].value;
		}
		
		addOrder(food);	// pass food object into addOrder function.
		
	});

	/**
	* set order food object into localStorage, named "list".
	*/
	function addOrder(food){ 
		//if localStorage("list") is empty, push food object into "list" array and localStorage.
		if(localStorage.getItem("list") === null){
			list.push(food); 
			localStorage.setItem("list",JSON.stringify(list));
			load();
			
		}else{ //else get current list and pass to check function
			list = JSON.parse(localStorage.getItem("list")); //get current list
			check(food);
			load();
		}
	}

	/**
	* check if data record is already exist in "list" array
	*/
	function check(food){
		console.log(list);
		var same = false;
		
		for(i=0;i<list.length;i++){
			if(list[i].foodId == food.foodId)
			{
				list[i].qty++; // add up qty num
				list[i].price=parseInt(list[i].price)+parseInt(food.price); // add up price 
				localStorage.setItem("list",JSON.stringify(list)); // save into localStorage
				same = true;
			}
		}
		if(!same){
			list.push(food);
			localStorage.setItem("list",JSON.stringify(list));
		}
	}

	/**
	* remove food from order list 
	*/
	$(document).on('click','.removeFood',function(){
		var id = $(this).attr("data-id");
		var foodList = JSON.parse(localStorage.getItem("list"));
		for(i=0;i<foodList.length;i++){
			if(foodList[i].foodId == id)
			{
				if(foodList[i].qty > 1){
						
					foodList[i].price-=(foodList[i].price/foodList[i].qty) // sub price
					foodList[i].qty -= 1;
					localStorage.setItem("list",JSON.stringify(foodList));
					console.log(localStorage.getItem("list"));
					load();
				}else if(foodList[i].qty == 1){
					delete foodList[i];
					foodList = JSON.stringify(foodList).replace(/null,|null|null\,|\,null/,''); // escaping the null
					localStorage.setItem("list",foodList);
					console.log(localStorage.getItem("list"));
					load();
				}
			}
		}		

	});


	/**
	* Checking the cart, see if is empty and minium order value before commit to submit.
	*/
	$(document).on('click','#submit',function(){

		var minOrder = $("#minOrder").attr("data-id");
		var totalPrice = parseInt($("#price").html());
		if($(".food").is(":empty")){
			alert("Can't be empty. Please add food!! ");
			return false;
		}
		if(totalPrice < minOrder){
			alert("min order is $"+minOrder);
			return false;
		}

	});

	/********* End of order cart functions *********/

	/**
	* display restaurants by order type, delivery,pick up.
	*/
	$(document).on('change','.orderType',function(){

		if($(this).prop('checked')==true){
			var cartType = $(this).val();
			$("#type").val(cartType);

			if(cartType == "delivery"){
				load();
				$(".delivery").css("display","block");
			}
			if( cartType == "pickUp"){
				load();
				$(".delivery").css("display","none");
			}

		}
	});
	

	/**
	* get restaurant data from localStorage and generate html.
	*/
	function load(){
		$('.food').empty();
		$('.submitOrder').empty();
		//console.log("ho"+localStorage.getItem("list"));
		var lists = JSON.parse(localStorage.getItem("list"));
		var restaurantId = $("#restaurant").attr("data-id");
		var cartType = $("#type").val(); // getting cart type

		if(cartType == "delivery"){ // if is delivery, price add up 5 dollar.
			var total = 5;
		}else{
			var total = 0;
		}

		if(lists !== null){
			for (i=0;i<lists.length;i++){
				if(lists[i].restaurantId == restaurantId){
					var data ='<li class="list-group-item" >'+ '<strong>'+lists[i].foodTitle +'</strong>'+
								'<button class="removeFood" data-id="'+lists[i].foodId+'">'+"x"+'</button>'+'</li>'+
								'<li class="list-group-item" >Price: '+lists[i].price+'</li>'+
								'<li class="list-group-item" >QTY: '+lists[i].qty+'</li>';
					
					total += parseInt(lists[i].price)*lists[i].qty; // total price
					$('.food').append(data);
				}
			}
			$('#total').html('<h4>Total:$</h4>'+'<strong id="price">'+total+'</strong>');
			$('#tPrice').append('<input type="hidden" id="t" name="totalPrice" value="'+total+'">');
		}
	}

	/**
	* getting order list from localStorage and append into page
	* checkOut.html
	*/
	var orderList = JSON.parse(localStorage.getItem("list"));
	var getRestaurantId = $("#getRestaurantId").attr("data-id");

	var cartType = $("#tp").val();
		if(cartType == "delivery"){
			var totalPrice = 5;
		}else{
			var totalPrice = 0;
		}

	for(i=0;i<orderList.length;i++){
			if(orderList[i].restaurantId == getRestaurantId){
				var food = '<li class="list-group-item" >'+ '<strong>'+orderList[i].foodTitle +'</strong>'+
									'<li class="list-group-item" >Price: '+orderList[i].price+'</li>'+
									'<li class="list-group-item" >QTY: '+orderList[i].qty+'</li>';

				var dataSubmit = '<input type="hidden" name="foodTitle[]" value="'+orderList[i].foodTitle+'">'+
									'<input type="hidden" name="foodId[]" value="'+orderList[i].foodId+'" >'+
									'<input type="hidden" name="price[]" value="'+orderList[i].price+'">'+
									'<input type="hidden" name="qty[]" value="'+orderList[i].qty+'">';
				totalPrice += parseInt(orderList[i].price)*orderList[i].qty;
				$('.submitOrder').append(dataSubmit);
				$('#orders').append(food);
			}
		}
	$('#totalPrice').html('<h4>Total:</h4>$'+'<strong id="prices">'+totalPrice+'</strong>');
	$('.payment').append('<input type="hidden" name="totalPrice" value="'+totalPrice+'">');
	$('.pay').val(totalPrice);


	/**
	* search for users
	*/
	$('#search').click(function(){
		var search = $('#searchInput').val();
		$.ajax({
			type: 'GET',
			url: '/auth/users/searchUser',
			data: {search: search},
			success:function(data){
				var user = JSON.parse(data);

				for(i=0;i<user.length;i++){
					$(".userTable").empty();
					var content = '<tr>'+
									'<td>'+user[i].id+'</td>'+
									'<td>'+user[i].userName+'</td>'+
									'<td>'+user[i].firstName+'</td>'+
									'<td>'+user[i].lastName+'</td>'+
									'<td>'+user[i].email+'</td>'+
									'<td>'+user[i].address+'</td>'+

									'<td><a href="/admin/'+user[i].id+'/editRestaurant">Show restaurant</a></td>'+
									'<td>'+user[i].validationCode+'</td>'+
									'<td>'+user[i].activate+'</td>'+
									'<td>'+user[i].role+'</td>'+
									'<td>'+
										'<form action="/admin/activateUser" method="POST">'+
											'<input type="hidden" name="email" value="'+user[i].email+'">'+
											'<input type="hidden" name="validationCode" value="'+user[i].validationCode+'">'+
											'<input class="btn btn-default" type="submit" name="activate" value="activate">'+
											'<input class="btn btn-danger" type="submit" name="deactivate" value="Deactivate">'+
										'</form>'
									'</td>'+
									'</tr>'
					$(".userTable").append(content);
				}
				console.log(user);
			}
		});
	});

	//reviews, star rating
	var num = $(".star").attr("data-id");

	var star = '<li class="full">'+'</li>';
		$(".empty").each(function(i){
			if(i!=num){
				$(this).removeClass("empty").addClass("full");
			}else{
				return false;
			}
		});
	
	/**
	* new restaurant upload function with image upload
	* Note: this function not in use
	*/
	// $("#upload").submit(function(){
	// 	event.preventDefault();

	// 	var fileInput = $('#file')[0];
	//     var data = new FormData();

	//     data.append('ufile',fileInput.files[0]);
	 	
	//  	var title 			= $('.addTitle').val();
	//  	var cuisineName 	= $('.addCuisineName').val(); 	
	//  	var openTime 		= $('.addOpenTime').val();
	//  	var minOrder 		= $('.addMinOrder').val();
	//  	var description		= $('.addDescription').val();
	//  	var address 		= $('.addAddress').val();
	//  	var cartType 		= $('.addCartType').val();
	//  	var userId 			= $('.userId').val();

	//  	data.append('title',title);
	//  	data.append('cuisineName',cuisineName);
	//  	data.append('openTime',openTime);
	//  	data.append('minOrder',minOrder);
	//  	data.append('description',description);
	//  	data.append('address',address);
	//  	data.append('cartType',cartType);
	//  	data.append('userId',userId);
	 	
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '/admin/addRestaurant',
	// 		data: data,
	// 		headers:{'Cache-Control':'no-cache'},
	// 		contentType:false,
 //        	processData:false,
	// 		success:function(data){
	// 			console.log(data);
	// 			var img = '<img src="'+data+'" width="120px">';
	// 			$('#perview').html(img);
	// 		}
	// 	});
	// });

	/**
	* image upload function via ajax
	*/
	$(".uploadImage").submit(function(){
		event.preventDefault();
		var fileInput = $(this).find(".imageFile")[0];
		var data = new FormData();
		data.append('ufile',fileInput.files[0]);
		var restaurantId = $(this).find(".restaurantId").val();
		data.append('restaurantId',restaurantId);
		
		$.ajax({
			type: 'POST',
			url: '/upload/uploadPhoto',
			data: data,
			headers:{'Cache-Control':'no-cache'},
			contentType:false,
        	processData:false,
			success:function(data){
				console.log(data);
				var img = '<img src="'+data+'" width="120px">';
				$('.perview').html(img);
			}
		});
		
	});

});
	

