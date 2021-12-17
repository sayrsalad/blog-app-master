$(document).ready(function () {
    $(".se-pre-con").fadeOut("slow");; // stop the loading
    // Filter out comments based on name and the comments itself
    // (Search function)
    $(".filter").on("keyup", function() {
        var input = $(this).val().toUpperCase();
        console.log(input);
        // .comments (class name yan naka foreach loop)
        $(".comments").each(function() {
            if ($(this).data("string").toUpperCase().indexOf(input) < 0) {
              $(this).hide();
            } else {
              $(this).show();
            }
        })
    });

    // Filter out comments result based on classification
    // Search function
    $('#selectClassification').on('change', function() {
        var classification = $('#selectClassification').val().toUpperCase();
        if (classification == 'POSITIVE' || classification == 'NEGATIVE' || classification == 'NEUTRAL') {
            console.log($('#selectClassification').val());
            $(".comments").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(classification) < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            })
        } else {
            $(".comments").each(function() {
                $(this).show();
            })
        }

    });


    //when the user like this post 
    $("#likeForm").submit(function(e) {
        e.preventDefault();
        var post_id = $('#post_id').val();
        $.ajax({
            method: 'POST',
            url: '/likePost',
            data: {
                post_id: post_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (result) {
                $('#likeNotificationContainer').html(''); 
                console.log(result);
                var html = '';
                if(result.errors) {
                    html += '<div class="alert alert-warning" role="alert">';
                    html += 'Error  on liking/unliking this post';
                    html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    html += '</div>';
                    $('#likeNotificationContainer').append(html); 
                } else if (result.success) {
                    if (result.isLike) {
                        $('#btnLike').html('<span class="fas fa-thumbs-up fa-2x" aria-hidden="true"></span>');
                        html += '<div class="alert alert-info" role="alert">';
                        html += 'You liked this post';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>'
                    } else {
                        $('#btnLike').html('<span class="far fa-thumbs-up fa-2x" aria-hidden="true"></span>');
                        html += '<div class="alert alert-secondary" role="alert">';
                        html += 'You unliked this post';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>'
                    }
                    $("#likeCount").html('Like Count: <strong>' +result.likeCount+'</strong>  || ');
                    $('#likeNotificationContainer').append(html); 
                }
            }
        })
    });
    
    // form for comment
    // magkaiba sila ng form since hindi modal yung ginamit sa pag write ng form
    $('#commentWriteForm').validate({ 
        rules: {
            replyFormComment: {
                required: true,
                minlength: 5,
                maxlength: 100,
            },
        },
        messages: {
            replyFormComment: {
                required: 'Comment field is required',
                minlength: 'The comment must be minimum 6 character long',
                maxlength: 'The comment should less than or equal to 100 characters',
            },
        },
        submitHandler: function() {
            $(".se-pre-con").fadeIn(); // start loading
            var comment = $('#replyFormComment').val()
            var data = {
                data: [comment],
            }
            $.ajax({
                url: 'https://api.monkeylearn.com/v3/classifiers/cl_pi3C7JiL/classify/',
                type: 'POST',
                data: JSON.stringify(data),
                dataType: 'json',
                headers: {
                    'Authorization': 'Token f2a33cb78661361ba6be6b76b3af8b4f176577cd',
                    'Content-Type': 'application/json'
                },
                success: function (result) {
                    var comment = result[0]['text'];
                    var classification = result[0]['classifications'][0]['tag_name'];
                    var confidence = result[0]['classifications'][0]['confidence'];
                    storeComment(comment, classification, confidence);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    })   

    function storeComment(comment, classification, confidence){
        var postId = $('#postId').val()

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
                    html += '<div class="alert alert-success" role="alert">';
                    html += 'Your comment has been successfuly submitted! <a href="/post/'+result.comment['post_id']+'" class="alert-link">Reload to see changes</a>';
                    html += '</div>';
                    $('#notificationContainer').append(html); 
                }
                $(".se-pre-con").fadeOut("slow"); // stop the loading once the comment has been stored
            }
        })
    }

    //when the user click the Like Count, the modal-list-like will show
    $('#btnShowLikeList').click(function(e) {
        e.preventDefault();
        var post_id = $('#post_id').val();
        $.ajax({
            method: 'GET',
            url: '/postLikes/'+post_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (result) {
                console.log(result);
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
                $('#likeListContainer').html(html);
                $('#listLikeModal').modal('show'); 
            }

        })
    })

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

    // when the user click the edit comment button
    $("form button[type=btnEditComment]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray() //get the form fields in serialize array

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

    // form for modal edit 
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
            $(".se-pre-con").fadeIn(); // start the loading
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
                    if (type === 'update') {
                        updateComment(comment, classification, confidence);
                    }
                    
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }) 

    function updateComment(comment, classification, confidence){
        var commentId = $('#modalCommentId').val()
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
                    $('#notificationUpdateDeleteCommentContainer').html(html); 
                } else if (result.success) {
                    var html = '';
                    html += '<div class="alert alert-success" role="alert">';
                    html += 'Your comment has been successfuly updated! <a href="/post/'+postId +'" class="alert-link">Reload this page to see changes</a>';
                    html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    html += '</div>';

                    $('#commentModal').modal('hide'); 
                    $('#notificationUpdateDeleteCommentContainer').html(html); 
                }

                $(".se-pre-con").fadeOut("slow");; // stop the loading
            }
        })
    }


    var formTarget; //initialise form target to access the variable globally
    // when the user clicks delete option on the post
    $("form button[type=btnDeletePost]").click(function(e) {
        e.preventDefault();
        var formValue = $(this.form).serializeArray()
        formTarget =  $(this.form);

        var postId = $('#postId').val();
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
                        $('#notificationDeletePostContainer').append(html); 
                    } else if (result.success) {
                        $('#confirmationDeleteModal').modal('hide'); 
                        formTarget.hide('slow', function(){ formTarget.remove(); });
            
                        html += '<div class="alert alert-success" role="alert">';
                        html += 'Your post has been successfuly deleted! <a href="/posts">Continue Browsing post</a>';
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
                        $('#notificationUpdateDeleteCommentContainer').append(html); 
                    } else if (result.success) {
                        $('#confirmationDeleteModal').modal('hide'); 
                        formTarget.hide('slow', function(){ formTarget.remove(); });
            
                        html += '<div class="alert alert-success" role="alert">';
                        html += 'Your comment has been successfuly deleted!';
                        html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        html += '</div>';
                
                        $('#notificationUpdateDeleteCommentContainer').append(html); 
                    }
                }
            })
        }
    });
});