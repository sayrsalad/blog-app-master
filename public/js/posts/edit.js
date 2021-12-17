$(document).ready(function() {
    $('#postForm').validate({ 
        
        rules: {
            title: {
                required: true,
                minlength: 5,
                maxlength: 100,
            },

            description: {
                required: true,
                minlength: 10,
                maxlength: 500,
            },
        },
        messages: {
            title: {
                required: 'Title field is required',
                minlength: 'The title must be minimum 6 character long',
                maxlength: 'The title should less than or equal to 100 characters',
            },
            description: {
                required: 'Description field is required',
                minlength: 'The description must be minimum 11 character long',
                maxlength: 'The description should less than or equal to 500 characters',
            },
        },
        submitHandler: function() {
            var description = $('#description').val();
            console.log($('#description').val())
            var data = {
                data: [description],
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
                    console.log(result);
                    var description = result[0]['text'];
                    var classification = result[0]['classifications'][0]['tag_name'];
                    var confidence = result[0]['classifications'][0]['confidence'];
                    storePost(description, classification, confidence);
                },
                error: function (error) {
                    console.log(error);
                }
            });

        }
    })

    function storePost(description, classification, confidence){
        var postId = $('#postId').val();
        var title = $('#title').val();

        $.ajax({
            method: 'POST',
            url: '/post/update',
            data: {
                postId: postId,
                title: title,
                description: description,
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
                    html += 'Your post failed to submit';
                    html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    html += '</div>';
                    $('#notificationContainer').html(html); 
                } else if (result.success) {
                    html += '<div class="alert alert-success" role="alert">';
                    html += 'Your post has been successfuly updated! <a href="/post/'+result.post['id']+'" class="alert-link">Click to see changes</a>';
                    html += '</div>';
                    $('#notificationContainer').html(html); 
                }
            }
        })
    }
})