
$(document).on('change','input[type="radio"]',function(){

	if($(this).prop('checked')==true){
		var cartType = $(this).val();
		$("#cartType").val(cartType);
		$(".main").empty().html(getRestaurant());
		// var	str = document.location.href;
		// var url = str.replace(/cartType\=[a-zA-Z]+/, "cartType="+cartType);
		// document.location=url;
	}
});


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
					var urlTitle = title.replace(/ /g,"_");
					var content =	'<tr>'+
									'<td>'+
										'<div class="row">'+
											'<div class="col-md-4">' +
								  				'<img class="img-rounded img" src="//www.menulog.com.au/generated_content/venue_images/takeaway_search_thumb/185433_ec3c411428f83918bb282fa725145a08/Bombay-Affair_Orderonline.jpeg">'+
								  			'</div>'+

								 			'<div class="col-md-8">'+
												'<h4>'+restaurant[i].title+'</h4>'+
												'<span>'+restaurant[i].cuisineName + '|</span>'+
												'<span>'+restaurant[i].openTime+ '| </span>'+
												'<span>$'+restaurant[i].minOrder+'</span> <br>'+
												'<span>'+restaurant[i].address+'</span> <br>'+
												'<a class="btn btn-default pull-right" href="/restaurants/'+urlTitle+'/menus" role="button">See Menu</a>'+
											'</div>'+
										'</div>'+
									'</td>'+
									'</tr>';
						
					$(".main").append(content);
				}
			}
		});
}


$(document).on('change','#all',function(){

	if($("#all").prop('checked')==true){
		
		$(".cuisineNames").not(this).prop('checked', false); 
		// $("#target").submit();
		$(".main").empty().html(getRestaurant());
	}

});


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
	var list = [];
	load();
	$(".foods").submit(function(e){
		e.preventDefault();
		var foodData = $(this).serializeArray();
		var food = {};
		
		for(i=0;i<foodData.length;i++){
			food[foodData[i].name]=foodData[i].value;
		}
		
		addOrder(food);
		
	});

	function addOrder(food){ 
		if(localStorage.getItem("list") === null){
			list.push(food);
			localStorage.setItem("list",JSON.stringify(list));
			load();
			
		}else{
			list = JSON.parse(localStorage.getItem("list")); //get current list
			check(food);
			load();
			console.log(list);
		}
	}

	function check(food){
		console.log(list);
		var same = false;
		
		for(i=0;i<list.length;i++){
			if(list[i].foodId == food.foodId)
			{
				list[i].qty++;
				list[i].price=parseInt(list[i].price)+parseInt(food.price);
				localStorage.setItem("list",JSON.stringify(list));
				same = true;
			}
		}
		if(!same){
			list.push(food);
			localStorage.setItem("list",JSON.stringify(list));
		}
	}

	function load(){
		$('.food').empty();
		var lists = JSON.parse(localStorage.getItem("list"));
		var restaurantId = $("#restaurant").attr("data-id");
		var total = 0;
		if(lists !== null){
			for (i=0;i<lists.length;i++){
				if(lists[i].restaurantId == restaurantId){
					var data ='<li class="list-group-item" >'+ '<strong>'+lists[i].foodTitle +'</strong>'+
								'<button class="removeFood" data-id="'+lists[i].foodId+'">'+"x"+'</button>'+'</li>'+
								'<li class="list-group-item" >Price: '+lists[i].price+'</li>'+
								'<li class="list-group-item" >QTY: '+lists[i].qty+'</li>';
					total += parseInt(lists[i].price);
					$('.food').append(data);
				}
			}
			$('#total').html('<strong>Total:</strong>$'+total);
		}
	}

	$(document).on('click','.removeFood',function(){
		var id = $(this).attr("data-id");
		var foodList = JSON.parse(localStorage.getItem("list"));
		for(i=0;i<foodList.length;i++){
			if(foodList[i].foodId == id)
			{
				if(foodList[i].qty > 1){
						
					foodList[i].price-=(foodList[i].price/foodList[i].qty)
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

	$(document).on('click','#submit',function(){
		var foodList = JSON.parse(localStorage.getItem("list"));
		
		$.ajax({
			type: 'POST',
			url: '/restaurants/orders',
			data: {orderList: foodList},
			success:function(data){
				console.log("yes");
				console.log(data);
			}
		});
	});

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
});
