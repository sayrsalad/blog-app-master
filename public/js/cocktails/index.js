$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()
    $.ajax({
        type: "GET",
        url: "/getTopics",
        dataType: 'json',
        success: function (response) {
            var cards = [];
            var topics = response.topics

            $.each(topics, function(index, value) {
                var topic = value;
                var drinkId = topic['drink_id'];
                var postCount = topic['post_count'];
                var positive =  topic['positive_count'];
                var neutral =  topic['neutral_count'];
                var negative =  topic['negative_count'];

                var url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=' + drinkId;
                cards.push(getData(url, postCount, positive, neutral, negative));
            }); 

            // INTAYIN NA MATAPOS YUNG AJAX REQUEST
            Promise.all(cards).then((allDrinksData) => {
                addCards(allDrinksData); // FUNCTION TO ADD THE ARRAY OF CARDS TO TOPICSCONTAINER
                $(".se-pre-con").fadeOut("slow");; // stop the loading
            })
        },
        error: function(){
            console.log('AJAX load did not work');
            alert("error");
        }
    });

    //add cards to topicsContainer
    function addCards(allDrinksData) {
        var card_list = '';
        card_list += '<div class="row">'
        
        for(i = 0; i < allDrinksData.length; i++) {
            card_list += '<div class="col-sm">'     
            card_list += allDrinksData[i]     
            card_list += '</div>'
            card_list += '<br>'
        } 
        card_list += '</div>'
        
        $('#topicsContainer').html(card_list);  
    }

    //get the data from theCocktailDb
    function getData(url, postCount, positiveCount, negativeCount, neutralCount) {
        return new Promise((resolve, reject) => {
            fetch(url)
                .then((resp) => resp.json())
                .then((data) => {
                    $.each(data.drinks, function(key, value) {
                        // var url = 'window.location.origin + '/meals/profile/' + value.idMeal'
                        var html = '';
                        
                        html += '<div class="card mt-3 dataFromDB" style="width: 20rem;" data-string="'+value.strDrink+' '+value.idDrink+' '+value.strCategory+' '+value.strGlass+'">'
                        html += '<img class="card-img-top" src="'+value.strDrinkThumb+'" alt="Card image cap">'
                        html += '<div class="card-body">'
                        html += '<h5 class="card-title">'+value.strDrink+' ('+value.idDrink+')</h5>'
                        html += '<p class="card-text">Category: '+value.strCategory+'</p>'
                        html += '<p class="card-text">Glass: '+value.strGlass+'</p>'
                        html += '<p class="card-text"><i class="fas fa-user-edit"></i> '+postCount+' blog post created</p>'
                        html += '<div class="progress" style="height: 40px;">'
                        html += '<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+positiveCount.toFixed()+'% Positive Post" role="progressbar" style="width: '+positiveCount.toFixed()+'%" aria-valuenow="'+positiveCount.toFixed()+'" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-smile-beam fa-2x"></i></div>'
                        html += '<div class="progress-bar progress-bar-striped bg-info progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+neutralCount.toFixed()+'% Neutral Post" role="progressbar" style="width: '+neutralCount.toFixed()+'%" aria-valuenow="'+neutralCount.toFixed()+'" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-meh fa-2x"></i></div>'
                        html += '<div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+negativeCount.toFixed()+'% Negative Post" role="progressbar" style="width: '+negativeCount.toFixed()+'%" aria-valuenow="'+negativeCount.toFixed()+'" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-angry fa-2x"></i></div>'
                        html += '</div>'
                        html += '<a href="/cocktail/'+value.idDrink+'" class="btn btn-primary mt-4 float-right">Browse</a>'
                        html += '</div>'
                        html += '</div>'
                        resolve(html);                        
                    })        
                })
        })
    }

    //SEARCH LANG DUN SA CARDS NA TOPICS CONTAINER
    $(".filter").on("keyup", function() {
        // FADE OUT TO ONLY SHOW TOPICS CONTAINER
        $("#singleDrinkContainer").fadeOut("slow");; // ffade out div container for single drink
        $("#drinksContainer").fadeOut("slow");; // fade out the div container for list of drinks from api
        var input = $(this).val().toUpperCase();

        // CLASS NAME YUNG dataFromDB (Tignan mo sa addcards() function)
        $(".dataFromDB").each(function() {
            if ($(this).data("string").toUpperCase().indexOf(input) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
        })
    });

    // SEACH BAR FROM API BALI KAPAG NG PRESS KEYUP/DOWN MA HIHIDE YUNG CONTAINER
    $("#searchBar").on("keyup", function() {
        $("#singleDrinkContainer").fadeOut("slow");; 
        $("#drinksContainer").fadeOut("slow");; 
    });

    //search directly from the api
    $('#btnSearchFromApi').click(function (e){
        var search = $('#searchBar').val();
        if(search !== null) {
            var url;
            if (!isNaN(search)) {
                //if the search is number (by drink_id)
                // Lookup full cocktail details by id
                url = '/searchByDrinkID/'+search;
                getSingleDrink(url);
            } else if (search.length === 1 && isNaN(search) === true) {
                //if the search is single character
                //List all cocktails by first letter
                url = '/searchByFirstLetter/'+search;
                getListDrinks(url);
            } else if (search.length > 1 && isNaN(search) === true) {
                //if the search is by name
                //https://www.thecocktaildb.com/api/json/v1/1/search.php?s=margarita
               url = '/searchByDrinkName/'+search;
               getListDrinks(url);
            }
        }
    })


    //Random Cocktail Button Listener
    $('#btnRandomCocktail').click(function (e){
        $("#drinksContainer").fadeOut("slow");; // stop the loading
        var url = '/getRandomCocktail';
        getSingleDrink(url);
        console.log('Random Cocktail');
    })

    // for searchByDrinkID and RandomCocktail
    function getSingleDrink(url) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function (response) {
                var drink = response.drink;
                //check if the reponse returns null
                if(drink != null) {
                    $('#searchBar').val(drink['strDrink']);
                    //if drink has content
                    var html = '';
                    html += '<div class="card mt-4">';
                    html += '<h5 class="card-header">Cocktail</h5>';
                    html += '<div class="card-body">';
                    html += '<div class="row">';
                    html += '<div class="col-6 col-md-4">';
                    html += '<img class="card-img-top" src="'+ drink['strDrinkThumb'] +'" alt="Card image cap">';
                    html += '</div>';
                    html += '<div class="col-12 col-md-8">';
                    html += '<h5 class="card-title">'+ drink['strDrink'] +' ('+ drink['idDrink'] +')</h5>';
                    html += '<p class="card-text">Category: '+ drink['strCategory'] +'</p>';
                    html += '<p class="card-text">Glass: '+ drink['strGlass'] +'</p>';
                    html += '<p class="card-text"><strong>Instruction: </strong>'+ drink['strInstructions'] +'</p>';
                    // check if the post count is 0
                    if (drink['postCount'] === 0) {
                        html += '<p class="card-text"><i class="fas fa-user-edit" aria-hidden="true"></i> No blog post created</p>'
                    } else {
                        html += '<p class="card-text"><i class="fas fa-user-edit" aria-hidden="true"></i> '+ drink['postCount'] +' blog post created</p>'
                        html += '<div class="progress" style="height: 40px;">'
                        html += '<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+ drink['positive'] +'% Positive Post" role="progressbar" style="width: '+ drink['positive'] +'%" aria-valuenow="'+ drink['positive'] +'" aria-valuemin="0" aria-valuemax="100">';
                        html += '<i class="fas fa-smile-beam fa-2x" aria-hidden="true"></i>';
                        html += '</div>';
                        html += '<div class="progress-bar progress-bar-striped bg-info progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+ drink['neutral'] +'% Neutral Post" role="progressbar" style="width: '+ drink['neutral'] +'%" aria-valuenow="'+ drink['neutral'] +'" aria-valuemin="0" aria-valuemax="100">';
                        html += '<i class="fas fa-meh fa-2x" aria-hidden="true"></i>';
                        html += '</div>';
                        html += '<div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+ drink['negative'] +'% Negative Post" role="progressbar" style="width: '+ drink['negative'] +'%" aria-valuenow="'+ drink['negative'] +'" aria-valuemin="0" aria-valuemax="100">';
                        html += '<i class="fas fa-angry fa-2x" aria-hidden="true"></i>';
                        html += '</div>';
                        html += '</div>';
                    }

                    html += '<div class="float-right">';
                    html += '<a href="/cocktail/'+ drink['idDrink'] +'" class="btn btn-primary mt-4">Browse</a>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div> ';
                    html += '</div>';
                    html += '</div>';
                    $('#singleDrinkContainer').html(html);
                    $("#singleDrinkContainer").fadeIn("slow");; // stop the loading
                } else {
                    //if drink doesn't have content
                    var message = '<div class="alert alert-warning" role="alert">';
                    message += 'No cocktails found!';
                    message += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    message += '<span aria-hidden="true">&times;</span>';
                    message += '</button>';
                    message += '</div>';
                    $('#notificationContainer').html(message);
                }
            },
            error: function(){
                console.log('AJAX load did not work');
                alert("error");
            }
        });
    }

    // for searchByFirstLetter and searchByDrinkName
    function getListDrinks(url) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function (response) {
                var cards = [];
                
                var drinks = response.drinks
                console.log(drinks);

                $.each(drinks, function(index, value) {
                    var drink = value;
                    var html = '';
                    var drink = value;
                    console.log(drink);
                    html += '<div class="card mt-3 dataFromApi" style="width: 20rem;" data-string="'+drink.strDrink+' '+drink.idDrink+' '+drink.strCategory+' '+drink.strGlass+' drinkFromDB">'
                    html += '<img class="card-img-top" src="'+drink.strDrinkThumb+'" alt="Card image cap">'
                    html += '<div class="card-body">'
                    html += '<h5 class="card-title">'+drink.strDrink+' ('+drink.idDrink+')</h5>'
                    html += '<p class="card-text">Category: '+drink.strCategory+'</p>'
                    html += '<p class="card-text">Glass: '+drink.strGlass+'</p>'
                    html += '<p class="card-text"><i class="fas fa-user-edit"></i> '+drink.postCount+' blog post created</p>'
                    if (drink.postCount > 0) {
                        html += '<div class="progress" style="height: 40px;">'
                        html += '<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+ drink.positive +'% Positive Post" role="progressbar" style="width: '+ drink.positive +'%" aria-valuenow="'+ drink.positive +'" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-smile-beam fa-2x"></i></div>'
                        html += '<div class="progress-bar progress-bar-striped bg-info progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+ drink.neutral +'% Neutral Post" role="progressbar" style="width: '+ drink.neutral +'%" aria-valuenow="'+ drink.neutral +'" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-meh fa-2x"></i></div>'
                        html += '<div class="progress-bar progress-bar-striped bg-danger progress-bar-animated" data-toggle="tooltip" data-placement="top" title="'+ drink.negative +'% Negative Post" role="progressbar" style="width: '+ drink.negative +'%" aria-valuenow="'+ drink.negative +'" aria-valuemin="0" aria-valuemax="100"><i class="fas fa-angry fa-2x"></i></div>'
                        html += '</div>'
                    }
                    
                    html += '<div class="float-right">'
                    html += '<a href="/cocktail/'+drink.idDrink+'" class="btn btn-primary mt-4">Browse</a>'
                    html += '</div>'
                    html += '</div>'
                    html += '</div>'
                    cards.push(html);
                })

                var card_list = '';
                card_list += '<div class="row">'
                
                for(i = 0; i < cards.length; i++) {
                    card_list += '<div class="col-sm">'     
                    // console.log(allDrinksData[i]);
                    card_list += cards[i]     
                    card_list += '</div>'
                    card_list += '<br>'
                } 

                card_list += '</div>'
                
                $('#drinksContainer').html(card_list);  
                $("#drinksContainer").fadeIn("slow");;

            },
            error: function(){
                console.log('AJAX load did not work');
                alert("error");
            }
        });
    }




})