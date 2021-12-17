$(document).ready(function() {
    function getDataOfPostID(formValue) {
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

    function getDataOfCommentID(formValue) {
        var values = {};

        $.each(formValue, function (i, field) {
            values[field.name] = field.value;
        });

        //Value Retrieval Function
        var getValue = function (valueName) {
            return values[valueName];
        };

        //Retrieve the Values
        var commentId = getValue("commentId");

        return commentId;
    }

    // show the list of like in a modal of a specific post
    $("form button[type=btnShowLikeList]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()

        var postId = getDataOfPostID(formValue);
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
                    html += '<img src="https://scontent.fmnl7-1.fna.fbcdn.net/v/t1.6435-9/69841700_2927629657254330_3139028448018694144_n.jpg?_nc_cat=109&amp;ccb=1-3&amp;_nc_sid=174925&amp;_nc_eui2=AeE9Ypckzov7JnwWDm7-wexCYlit_vP-hZ1iWK3-8_6FnSmKC4Tq40bYzAunEOsydYhnGfwAN47kTgEash53IgRn&amp;_nc_ohc=dxJwOoh0w5AAX-tXuZN&amp;_nc_ht=scontent.fmnl7-1.fna&amp;oh=2abfa03f9ef65e906dd9a8312f181c5d&amp;oe=6088CA85" width="50" height="50" class="rounded-circle" alt="">';
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

        var postId = getDataOfPostID(formValue);
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
                    html += '<img src="https://scontent.fmnl7-1.fna.fbcdn.net/v/t1.6435-9/69841700_2927629657254330_3139028448018694144_n.jpg?_nc_cat=109&amp;ccb=1-3&amp;_nc_sid=174925&amp;_nc_eui2=AeE9Ypckzov7JnwWDm7-wexCYlit_vP-hZ1iWK3-8_6FnSmKC4Tq40bYzAunEOsydYhnGfwAN47kTgEash53IgRn&amp;_nc_ohc=dxJwOoh0w5AAX-tXuZN&amp;_nc_ht=scontent.fmnl7-1.fna&amp;oh=2abfa03f9ef65e906dd9a8312f181c5d&amp;oe=6088CA85" width="50" height="50" class="rounded-circle" alt="">';
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
    
    // when the user press the like icon
    $("form button[type=btnLike]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()
        var postId = getDataOfPostID(formValue);
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

    
    var idCommentButton; //set global to access it in storeComment function
    var idCommentCounter; //set global to access it in storeComment function
    var idNotificationContainer; 

    // when the user click the comment icon, the form value postId will pass to the modal form comment
    $("form button[type=btnComment]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()

        //Retrieve the Values
        postId = getDataOfPostID(formValue);
        console.log(postId);

        $('#commentModalLabel').html('<i class="fas fa-comment-medical mr-2"></i>Write Comment'); 
        $('#btnModalSubmitComment').html('<i class="fas fa-paper-plane"></i>'); 
        $('#modalPostId').val(postId); 
        $('#btnModalSubmitComment').val('create');  
        $('#commentModal').modal('show'); 
    });

    // when the user click the edit comment button
    $("form button[type=btnEditComment]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()

        //Retrieve the Values
        commentId = getDataOfCommentID(formValue);
        console.log(commentId);
        $.ajax({
            type: 'GET',
            url: '/comment/edit/'+commentId,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var comment = response.comment;
                $('#commentModalLabel').html('<i class="fas fa-comment-medical mr-2"></i>Edit Comment'); 
                $('#btnModalSubmitComment').html('<i class="fas fa-paper-plane"></i>'); 
                $('#modalCommentId').val(comment['id']); 
                $('#modalComment').val(comment['comment']); 
                $('#btnModalSubmitComment').val('update'); 
                $('#commentModal').modal('show'); 
            }
        })
    });

    // when the user submit the comment
    // use post request to pass the comment to the text sentiment apo
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
        submitHandler: function(e) {
            var type = $('#btnModalSubmitComment').val(); 
            var comment = $('#modalComment').val();

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
                    if (type === 'create') {
                        storeComment(comment, classification, confidence);
                    } else if (type === 'update') {
                        updateComment(comment, classification, confidence);
                    }
                    
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }) 
    
    // function for passing the values from the text sentiment api to the database
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
            }
        })
    }

    function updateComment(comment, classification, confidence){
        var commentId = $('#modalCommentId').val()
        var postId = $('#modalPostId').val()
        $.ajax({
            method: 'POST',
            url: '/comment/update',
            data: {
                commentId: commentId,
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
                    $('#notificationUpdateCommentContainer').html(html); 
                } else if (result.success) {
                    var html = '';
                    $(idCommentButton).html('<span class="fas fa-comment fa-2x" aria-hidden="true"></span>');
                    html += '<div class="alert alert-success" role="alert">';
                    html += 'Your comment has been successfuly submitted! <a href="/post/'+postId +'" class="alert-link">Click this to see your comment</a>';
                    html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    html += '</div>';

                    $('#commentModal').modal('hide'); 
                    $('#notificationUpdateCommentContainer').html(html); 
                }
            }
        })
    }

    // when the user click the delete botton, delete modal will show to ask for confirmation
    //

    var formTarget; //initialise form target to access the variable globally
    // when the user clicks delete option on the post
    $("form button[type=btnDeletePost]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()
        formTarget =  $(this.form);

        var postId = getDataOfPostID(formValue);
        $('#modalDelPostId').val(postId);
        $('#confirmationMessage').html('<p>Do you really want to delete this post? This process cannot be undone.</p>')
        $('#btnDelete').val('post');
        $('#confirmationDeleteModal').modal('show');    
    })

    // when the user clicks delete option on the comments
    $('form button[type=btnDeleteComment]').click(function (e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()
        formTarget =  $(this.form);

        var commentId = getDataOfCommentID(formValue);
        $('#modalDelCommentId').val(commentId);
        $('#confirmationMessage').html('<p>Do you really want to delete this comment? This process cannot be undone.</p>')
        $('#btnDelete').val('comment');
        $('#confirmationDeleteModal').modal('show'); 
    })

    // when the user confirms that he/she wants to delete the post/comments
    // confirmation modal applies to comments and posts
    $("#modalDeleteForm").submit(function(e) {
        e.preventDefault();
        var type = $('#btnDelete').val(); //check what user is gonna delete
        // if the type is post
        if (type === 'post') {
            //then it will delete post
            var post_id = $('#modalDelPostId').val(); //get the post id from the hidden input from modal
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
                
                        $('#notificationDeletePostContainer').append(html); 
                    }
                    // scroll to the top of the page to see the notificationDeleteContainer
                    window.scrollTo(0, 0);
                }
            })
        } else if (type === 'comment') { // if the type is comment
            //then it will delete comment
            var comment_id = $('#modalDelCommentId').val();
            $.ajax({
                method: 'POST',
                url: '/comment/destroy',
                data: {
                    comment_id: comment_id,
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
                        $('#notificationDeleteCommentContainer').append(html); 
                    } else if (result.success) {
                        $('#confirmationDeleteModal').modal('hide'); 
                        formTarget.hide('slow', function(){ formTarget.remove(); });
            
                        html += '<div class="alert alert-success" role="alert">';
                        html += 'Your post has been successfuly deleted!';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';
                
                        $('#notificationDeleteCommentContainer').append(html); 
                    }
                }
            })
        }

        
    });
})