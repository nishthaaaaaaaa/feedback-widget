<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .btn:hover {
        background-color: rgba(0, 0, 0, 0.288);
        border: none;
        font-size: 18px;
        transition: 0.5s;
    }

    body {
        background: linear-gradient(to bottom, #fff, #6c6c6c);
    }

    label {
        color: #fff;
    }

    #form-model {
        background: #c6c6c684
    }
</style>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden sm:rounded-lg">
            <div class="d-flex justify-content-between" style="margin: 40px;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div>
                    <a class="btn btn-secondary m-2" href="javascript:void(0)" id="open">Add your feedback</a>
                </div>
                <div>
                    <a class="btn btn-secondary m-2" href="{{route('register')}}" id="open">Register</a>
                    <a class="btn btn-secondary m-2" href="{{route('login')}}" id="open">Login</a>
                </div>
            </div>
            <div id="form-model" class=" bg-secondary bg-opacity-50" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" aria-label="Close" id="close"></button>
                    </div>
                    <form id="form" method="POST" class="container mt-5">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{ old('name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Rate</label>
                            <div class="star-rating">
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star" class="star">&#9733;</label>

                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars" class="star">&#9733;</label>

                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars" class="star">&#9733;</label>

                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars" class="star">&#9733;</label>

                                <input type="radio" id="star5" name="rating" value="5" />
                                <label for="star5" title="5 stars" class="star">&#9733;</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" name="comment" id="comment" rows="4"
                                value="{{ old('comment') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-light text-dark">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    #form-model {
        position: fixed;
        width: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        display: none;
        z-index: 9999;
        border-radius: 8px;
    }

    .star-rating {
        display: flex;
        flex-direction: row;
        justify-content: start;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 2rem;
        color: #fff;
        cursor: pointer;
        transition: color 0.3s;
    }

    /* Highlight stars when selected or hovered */
    .star-rating input:checked~label,
    .star-rating label:hover,
    .star-rating label:hover~label {
        color: gold;
    }
</style>
<script>
    $(function () {
        $('#open').click(function () {
            $('#form-model').fadeIn();
        });
        $('#close').click(function () {
            $('#form-model').fadeOut();
        });

        $('#form').submit(function (e) {
            e.preventDefault();
            let name = $('#name').val().trim();
            let email = $('#email').val().trim();
            let rating = $('input[name="rating"]:checked').val();
            let comment = $('#comment').val().trim();

            let isValid = true;
            if (name === '') {
                $('#name').addClass('is-invalid')
                    .after('<p class="text-danger">Name cannot be empty</p>');
                isValid = false;
            }
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === '') {
                $('#email').addClass('is-invalid')
                    .after('<p class="text-danger">Email cannot be empty</p>');
                isValid = false;
            } else if (!emailPattern.test(email)) {
                $('#email').addClass('is-invalid')
                    .after('<p class="text-danger">Enter a valid email</p>');
                isValid = false;
            }
            if (!rating) {
                $('input[name="rating"]').last().after('<p class="text-danger d-block">Please select a rating</p>');
                isValid = false;
            }
            if (comment === '') {
                $('#comment').addClass('is-invalid')
                    .after('<p class="text-danger">Comment cannot be empty</p>');
                isValid = false;
            }
            if (!isValid) {
                return;
            }


            const formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route('feedback.add') }}',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // console.log(response);
                    $('#form-model').fadeOut();
                    document.getElementById('form').reset();
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        });
    });
</script>