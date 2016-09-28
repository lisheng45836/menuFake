
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
					var content = '<li><h2>'+restaurant[i].title+'<h2></li>'+
								'<li>cuisineName:'+restaurant[i].cuisineName+'</li>'+
								'<li>openTime:'+ restaurant[i].openTime+'</li>'+
								'<li>minOrder:'+ restaurant[i].minOrder+'</li>'+
								'<li>description:'+ restaurant[i].description+'</li>'+
								'<li>image_path:'+ restaurant[i].image_path+'</li>'+
								'<li>address:'+ restaurant[i].address+'</li>'+
								'<li>cartType:'+ restaurant[i].cartType+ '</li>'+
								'<a href="/restaurants/'+urlTitle+'">'+"See Menu"+'</a>';
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
					var data ='<li>'+ lists[i].foodTitle +'</li>'+
								'<li>Price: '+lists[i].price+'</li>'+
								'<li>QTY: '+lists[i].qty+'</li>'+
								'<button class="removeFood" data-id="'+lists[i].foodId+'">'+"x"+'</button>';
					total += parseInt(lists[i].price);
					$('.food').append(data);
				}
			}
			$('#total').html('Total:'+total);
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
					load();
				}else if(foodList[i].qty == 1){
					delete foodList[i];
					foodList = JSON.stringify(foodList).replace(/null|null\,|\,null/,''); // escaping the null
					localStorage.setItem("list",foodList);
					console.log(localStorage.getItem("list"));
					load();
				}
			}
		}		

	});

});
