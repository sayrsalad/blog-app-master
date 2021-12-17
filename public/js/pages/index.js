$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()
    $.ajax({
        type: "GET",
        url: "/cocktails",
        dataType: 'json',
        success: function (data) {
            var drinks = data;
            var cards = [];
            
            $.each(drinks, function(index, value) {
                // console.log(index, value);
                var postCount = value[0], positiveCount = (value[1] / postCount) * 100 , negativeCount = (value[2] / postCount) * 100, neutralCount = (value[3] / postCount) * 100;
                var url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=' + index;
                cards.push(getData(url, postCount, positiveCount, negativeCount, neutralCount))
            });

            Promise.all(cards).then((allDrinksData) => {
                addCards(allDrinksData);
            })
        },
        error: function(){
            console.log('AJAX load did not work');
            alert("error");
    
        }
    });

    $(".filter").on("keyup", function() {
        var input = $(this).val().toUpperCase();
        console.log(input);

        $(".card").each(function() {
            if ($(this).data("string").toUpperCase().indexOf(input) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
        })
    });

    function addCards(allDrinksData) {
        var card_list = '';
        card_list += '<div class="row">'
        
        for(i = 0; i < allDrinksData.length; i++) {
            card_list += '<div class="col-sm">'     
            // console.log(allDrinksData[i]);
            card_list += allDrinksData[i]     
            card_list += '</div>'
            card_list += '<br>'
        } 
        card_list += '</div>'
        
        $('#card_list').append(card_list);  
    }

    function getData(url, postCount, positiveCount, negativeCount, neutralCount) {
        return new Promise((resolve, reject) => {
            fetch(url)
                .then((resp) => resp.json())
                .then((data) => {
                    $.each(data.drinks, function(key, value) {
                        // var url = 'window.location.origin + '/meals/profile/' + value.idMeal'
                        var html = '';
                        
                        html += '<div class="card mt-3" style="width: 20rem;" data-string="'+value.strDrink+' '+value.idDrink+' '+value.strCategory+' '+value.strGlass+'">'
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
                        // $('#card_list').append(html);
                        
                    })        
                })
        })
    }

})