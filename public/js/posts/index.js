$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: "/getPosts",
        dataType: 'json',
        success: function (data) {
            var cards = [];
            console.log(data);
            $.each(data.posts, function(index, value) {
                var post = value;
                var url = 'https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=' + post['drink_id'];
                cards.push(getData(url, post))
            });

            Promise.all(cards).then((allDrinksData) => {
                addCards(allDrinksData);
                loadFunctions(); // just to make sure to load all cards before applying this funtion
                $(".se-pre-con").fadeOut("slow"); // stop the loading
            })
        },
        error: function(){
            console.log('AJAX load did not work');
            alert("error");
    
        }
    });
    
    $(".filter").on("keyup", function() {
        var input = $(this).val().toUpperCase();

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
        
        for(i = 0; i < allDrinksData.length; i++) {   
            card_list += allDrinksData[i]     
        } 
        
        $('#card_list').html(card_list);  
    }

    function getData(url, post) {
        return new Promise((resolve, reject) => {
            fetch(url)
                .then((resp) => resp.json())
                .then((data) => {
                    $.each(data.drinks, function(key, value) {
                        var html = '';
                        
                        html += '<form>';
                        html += '<input type="hidden" name="postId" value="'+post['id']+'"/>';
                        html += '<div class="card border-dark mb-3" style="" data-string="'+value.strDrink+' '+value.idDrink+' '+value.strCategory+' '+value.strGlass+ ' ' +post['title']+' '+post['user']['name']+' '+post['user']['lastname']+' '+post['desc']+' '+post['user']['title'] +' '+post['user']['name'] +' '+post['user']['lastname'] +'">'; 
                        html += '<div class="card-header">Blog post #'+post['id']; // start of card headear
                        // check if the user owns the post to show edit and delete option
                        if (post['self_post']) {
                            html += '<div class="dropdown float-right dropleft">';
                            html += '<button class="btn btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                            html += '<i class="fas fa-ellipsis-h"></i>';
                            html += '</button>';
                            html += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                            html += '<a class="dropdown-item" href="/post/edit/'+post['id']+'"><span class="far fa-edit" aria-hidden="true"></span> Edit post</a>';
                            html += '<button type="btnDeletePost" class="dropdown-item" type="button">';
                            html += '<span class="far fa-trash-alt" aria-hidden="true"></span> Remove';
                            html += '</button> ';
                            html += '</div>';
                            html += '</div>';
                        }
                        html += '</div>'; // end of card header
                        html += '<div class="mt-2" id="notificationContainer'+post['id']+'"></div>'; //Notification container when the user submitted the comment
                        html += '<div class="row">'; 
                        html += '<div class="col-auto">'; // for drink info
                        html += '<div class="card border-dark border-0 ml-3 mt-2" style="width: 18rem;"  data-string="'+value.strDrink+' '+value.idDrink+' '+value.strCategory+' '+value.strGlass+ ' ' +post['title']+' '+post['user']['name']+' '+post['user']['lastname']+' '+post['desc']+' '+post['user']['title'] +' '+post['user']['name'] +' '+post['user']['lastname'] +'">'; 
                        html += '<img class="card-img-top" src="'+value.strDrinkThumb+'" alt="Card image cap">';
                        html += '<div class="card-body">';
                        html += '<h5 class="card-title"><a href="/cocktail/'+value.idDrink+'">Topic: '+value.strDrink+'</a></h5>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>'; // end for drink info
                        html += '<div class="col-8">'; // for post info
                        html += '<div class="card-body text-dark">';
                        html += '<div class="row">';
                        html += '<div class="col-auto">';
                        // user picture href
                        html += '<img src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" width="50" height="50" class="rounded-circle" alt="">';
                        html += '</div>';
                        html += '<div class="col-8">';
                        html += '<span class="d-inline-block">';
                        html += '<span class="ml-0"><a href="/user/'+post['user']['id']+'"><strong>'+post['user']['title'] +' '+post['user']['name'] +' '+post['user']['lastname'] +'</strong></a></span><br>';
                        
                        html += '<footer class="blockquote-footer">Date: '+ post['created_at'] +'</footer>';
                        html += '</span>';
                        html += '</div>';
                        html += '</div>';

                        html += '<h5 class="card-title mt-3">'+ post['title'] +'</h5>';
                        html += '<p><em>Classification: '+ post['classification'] +' | Confidence: '+ (post['confidence'] * 100).toFixed() +'%</em></p>';
                        html += '<p class="card-text text-justify pb-0">' + post['desc'] + '</p>';
                        html += '<div class="d-flex justify-content-between mb-2">';
                        html += '<div>';
                        html += '<button type="btnShowLikeList" class="btn btn-link">'; // button to show list of user who likes the post
                        html += '<i class="fas fa-thumbs-up"></i><span class="ml-1" id="likeCounter'+ post['id'] +'">' + post['like_count'] + ' Likes</span>';           
                        html += '</button>';
                        html += '</div>';
                        html += '<div>';
                        html += '<button type="btnShowCommentList" class="btn btn-link">'; // button to show list of user who comments the post
                        html += '<i class="fas fa-comment"></i><span class="ml-1" id="commentCounter'+ post['id'] +'">' + post['comment_count'] + ' Comments</span>';
                        html += '</button>';
                        html += '</div>';
                        html += '</div>';

                        html += '<div class="row">';
                        html += '<div class="col-sm text-center">';
                        html += '<button type="btnLike"  class="btn btn-link" id="btnLike'+ post['id'] +'">';
                        //check if the user liked the post
                        if (post['self_like']) {
                            // fill the color of the font awesome
                            html += '<span class="fas fa-thumbs-up fa-2x" aria-hidden="true"></span>';           
                        } else {
                            // doesnt fill the color
                            html += '<span class="far fa-thumbs-up fa-2x" aria-hidden="true"></span>'; 
                        }
                        html += '</button>';
                        html += '</div>';
                        html += '<div class="col-sm text-center">';
                        html += '<button type="btnComment" class="btn btn-link" id="btnComment'+ post['id'] +'">';
                        //check if the user commented already on this post
                        if (post['isCommented']) {
                            // fill the color of the font awesome
                            html += '<span class="fas fa-comment fa-2x" aria-hidden="true"></span>';        
                        } else {
                            // doesnt fill the color
                            html += '<span class="far fa-comment fa-2x" aria-hidden="true"></span>';
                        }
                        html += '</button>';
                        html += '</div>';
                        html += '<div class="col-sm text-center">';
                        html += '<a href="/post/'+ post['id'] +'"><i class="far fa-eye fa-2x" aria-hidden="true"></i></a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</form>';
                        // $('#card_list').append(html);
                        resolve(html);  
                    })        
                })
        })
    }

    var formTarget; //initialise form target to access the variable globally
    var idCommentButton; //set global to access it in storeComment function
    var idCommentCounter; //set global to access it in storeComment function
    var idNotificationContainer; 
    function loadFunctions() {

        function getDataOfPostCard(formValue) {
            var values = {};
    
            $.each(formValue, function (i, field) {
                values[field.name] = field.value;
            });
    
            //Value Retrieval Function
            var getValue = function (valueName) {
                return values[valueName];
            };
    
            //Retrieve the Values
            var postId = getValue("postId");
    
            return postId;
        }
        
        $("form button[type=btnDeletePost]").click(function(e) {
            e.preventDefault();
            var formValue = $(this.form).serializeArray()
            formTarget =  $(this.form);
    
            var postId = getDataOfPostCard(formValue);
            $('#modalDelPostId').val(postId);
            
            $('#confirmationDeleteModal').modal('show');    
        })

        $("#modalDeleteForm").submit(function(e) {
            e.preventDefault();
            var post_id = $('#modalDelPostId').val();

            $.ajax({
                method: 'POST',
                url: '/post/destroy',
                data: {
                    post_id: post_id,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    var html = '';
                    if(result.errors) {
                        html += '<div class="alert alert-warning" role="alert">';
                        html += 'Your post failed to be deleted';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';
                        $('#notificationContainer').append(html); 
                    } else if (result.success) {
                        $('#confirmationDeleteModal').modal('hide'); 
                        formTarget.hide('slow', function(){ formTarget.remove(); });
            
                        html += '<div class="alert alert-success" role="alert">';
                        html += 'Your comment has been successfuly deleted!';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';
                
                        $('#notificationContainer').append(html); 
                    }
                    window.scrollTo(0, 0);
                }
            })    
        });

        // show the list of like in a modal of a specific post
        $("form button[type=btnShowLikeList]").click(function(e) {
            e.preventDefault();
            var formValue = $(this.form).serializeArray()
    
            var postId = getDataOfPostCard(formValue);
            console.log(postId);

            $.ajax({
                method: 'GET',
                url: '/postLikes/'+postId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    $('#likeCountModalLabel').html('<i class="fas fa-thumbs-up"></i> '+result.like_count);
                    var html = '';
                    html += '<ul class="list-group list-group-flush">';
                    $.each(result.likes, function(index, value) {
                        var post = value;
                        console.log(value['id']);
                        html += '<li class="list-group-item">';
                        html += '<ul class="list-inline">';
                        html += '<li class="list-inline-item float-left">';
                        html += '<img src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" width="50" height="50" class="rounded-circle" alt="">';
                        html += '</li>';
                        if (value['self_like']) {
                            html += '<li class="list-inline-item"><a href="/user/'+value['user_id']+'">'+ value['user']['title']+' '+value['user']['name']+' '+value['user']['lastname'] +'</a><strong>  (You)</strong><br>';
                    
                        } else {
                            html += '<li class="list-inline-item"><a href="/user/'+value['user_id']+'">'+ value['user']['title']+' '+value['user']['name']+' '+value['user']['lastname'] +'</a><br>';
                        }
                        
                        html += '<footer class="blockquote-footer">Date: '+value['created_at']+'</footer>';
                        html += '</li>';
                        html += '</ul>';
                        html += '</li>';
                    });
                    console.log(html);
                    $('#likeListContainer').html(html);
                    $('#listLikeModal').modal('show'); 
                }

            })
        })

        // when the user click comments on the specific post
        // it will show the list of comments in modal form
        $("form button[type=btnShowCommentList]").click(function(e) {
            e.preventDefault();
            var formValue = $(this.form).serializeArray();

            var postId = getDataOfPostCard(formValue);
            console.log(postId);
            $('#listCommentModal').modal('show'); 
            $('#commentCountModalLabel').html('<i class="fas fa-comments"></i> '+7);
            $.ajax({
                method: 'GET',
                url: '/postComments/'+postId,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    $('#commentCountModalLabel').html('<i class="fas fa-thumbs-up"></i> '+result.comment_count);
                    var html = '';
                    $.each(result.comments, function(index, value) {
                        var comment = value;
                        html += '<div class="card-body text-dark comments" data-string="'+comment['self_comment']+' '+comment['classification']+'">';
                        html += '<span class="d-inline-block">';
                        html += '<img src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg" width="50" height="50" class="rounded-circle" alt="">';
                        html += '</span>';
                        html += ' <span class="d-inline-block">';

                        if (comment['self_comment']) {
                            html += '<span class="ml-2"><a href="user/'+comment['user_id']+'"><strong>'+ comment['user']['title']+' '+comment['user']['name']+' '+comment['user']['lastname'] +'</strong></a>(You)</span><br>';
                            
                        } else {
                            html += '<span class="ml-2"><a href="user/'+comment['user_id']+'"><strong>'+ comment['user']['title']+' '+comment['user']['name']+' '+comment['user']['lastname'] +'</strong></a></span><br>';
                        }
                        
                        html += '</span>';
                        html += '<br>';
                        html += '<h6 class="card-subtitle mt-1 text-muted">'+comment['comment']+'</h6>';
                        html += '<p class="card-text"><small>Type: '+comment['classification']+' -- Confidence: '+(comment['confidence'] * 100).toFixed()+'% </small></p>';
                        html += '<footer class="blockquote-footer">'+comment['created_at']+'</footer>';
                        html += '</div>';
                    })
                    $('#commentListContainer').html(html);
                    $('#listCommentModal').modal('show'); 
                }
            })
        })

        //sort function in modal form
        function sortCommentsList(input) {
            $(".comments").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input.toUpperCase()) < 0) {
                  $(this).hide();
                } else {
                  $(this).show();
                }
            })

        }
        // when the user click (all) in the list of comments in modal form
        // sort this 
        $('#btnAllComments').click(function(e) {
            e.preventDefault();
            $(".comments").each(function() {
                $(this).show();
            })
        })

        //  when the user click to show his comments
        $('#btnYourComments').click(function(e) {
            e.preventDefault();
            sortCommentsList('true');
        })

        //  when the user click to show positive comments
        $('#btnPositiveComments').click(function(e) {
            e.preventDefault();
            sortCommentsList('positive');
        })

        //  when the user click to show neutral comments
        $('#btnNeutralComments').click(function(e) {
            e.preventDefault();
            sortCommentsList('neutral');
        })

        //  when the user click to show negative comments
        $('#btnNegativeComments').click(function(e) {
            e.preventDefault();
            sortCommentsList('negative');
        })

    
    
        $("form button[type=btnLike]").click(function(e) {
            e.preventDefault();
            var formValue = $(this.form).serializeArray()
            var postId = getDataOfPostCard(formValue);
            console.log(postId);
            var idNotificationContainer = '#notificationContainer'+postId
            var idLikeButton = "#btnLike"+postId
            var idLikeCounter = "#likeCounter"+postId
            
            $.ajax({
                method: 'POST',
                url: '/likePost',
                data: {
                    post_id: postId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    var html = '';
                    if(result.errors) {
                        html += '<div class="alert alert-warning" role="alert">';
                        html += 'Error  on liking/unliking this post';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';
                        $(idNotificationContainer).append(html); 
                    } else if (result.success) {
                        if (result.isLike) {
                            $(idLikeButton).html('<span class="fas fa-thumbs-up fa-2x" aria-hidden="true"></span>');
                            html += '<div class="alert alert-info" role="alert">';
                            html += 'You liked this post';
                            html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                            html += '</div>'
                        } else {
                            $(idLikeButton).html('<span class="far fa-thumbs-up fa-2x" aria-hidden="true"></span>');
                            html += '<div class="alert alert-secondary" role="alert">';
                            html += 'You unliked this post';
                            html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                            html += '</div>'
                        }
                        $(idLikeCounter).html(result.likeCount+' Likes');
                        $(idNotificationContainer).html(html); 
                    }
                }
            })

            // $(idLikeButton).html('<span class="fas fa-thumbs-up fa-2x" aria-hidden="true"></span>');
            //  html += 'Your comment has been successfuly submitted! <a href="/blog/'+postId+'" class="alert-link">Click to see the comment</a>';
        });
        
        $("form button[type=btnComment]").click(function(e) {
            e.preventDefault();
            var formValue = $(this.form).serializeArray()
    
            //Retrieve the Values
            postId = getDataOfPostCard(formValue);
            console.log(postId);
    
            $('#commentModalLabel').html('<i class="fas fa-comment-medical mr-2"></i>Write Comment'); 
            $('#btnModalSubmitComment').html('<i class="fas fa-paper-plane"></i>'); 
            $('#modalPostId').val(postId); 
            $('#commentModal').modal('show'); 
        });
        
        
        $('#commentForm').validate({ 
            rules: {
                modalComment: {
                    required: true,
                    minlength: 5,
                    maxlength: 100,
                },
            },
            messages: {
                modalComment: {
                    required: 'Comment field is required',
                    minlength: 'The certificate name must be minimum 6 character long',
                    maxlength: 'The certificate name should less than or equal to 100 characters',
                },
            },
            submitHandler: function() {
                $(".se-pre-con").fadeIn();
                var comment = $('#modalComment').val()
                var postId = $('#modalPostId').val()
                var data = {
                    data: [comment],
                }
                $.ajax({
                    url: 'https://api.monkeylearn.com/v3/classifiers/cl_pi3C7JiL/classify/',
                    type: 'POST',
                    data: JSON.stringify(data),
                    dataType: 'json',
                    headers: {
                        'Authorization': 'Token 67c90fa0e56f1ce6b184391c58161e647ce40e46',
                        'Content-Type': 'application/json'
                    },
                    success: function (result) {
                        var comment = result[0]['text'];
                        var classification = result[0]['classifications'][0]['tag_name'];
                        var confidence = result[0]['classifications'][0]['confidence'];
                        idNotificationContainer = '#notificationContainer'+postId
                        idCommentButton = "#btnComment"+postId //set
                        idCommentCounter = "#commentCounter"+postId //set
                        storeComment(comment, classification, confidence);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        }) 
        
        function storeComment(comment, classification, confidence){
            var postId = $('#modalPostId').val()
    
            $.ajax({
                method: 'POST',
                url: '/comment/store',
                data: {
                    postId: postId,
                    comment: comment,
                    classification: classification,
                    confidence: confidence
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (result) {
                    console.log(result);
                    var html = '';
                    if(result.errors) {
                        html += '<div class="alert alert-warning" role="alert">';
                        html += 'Your comment failed to submit';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';
                        $('#notificationContainer').append(html); 
                    } else if (result.success) {
                        var html = '';
                        $(idCommentButton).html('<span class="fas fa-comment fa-2x" aria-hidden="true"></span>');
                        html += '<div class="alert alert-success" role="alert">';
                        html += 'Your comment has been successfuly submitted! <a href="/post/'+postId +'" class="alert-link">Click this to see your comment</a>';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';

                        $('#commentModal').modal('hide'); 
                        $(idCommentCounter).html(result.comment_count+' Comments');
                        $(idNotificationContainer).html(html); 

                    }
                    $(".se-pre-con").fadeOut("slow");
                }
            })
        }
    }

})