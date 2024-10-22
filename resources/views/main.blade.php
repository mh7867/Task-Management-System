<!doctype html>
<html lang="en">

<head>
    @stack('title')
    <!-- Required meta tags -->
    <meta charset="utf-8">
    @stack('meta')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -- CDNs -- Here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Italiana&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('storage/css/custom-style.css') }}">
    @stack('style')
</head>

<body>

    @yield('content')

    <!-- Optional JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js?ver=1.0.0"
        id="jquery-2-js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js?ver=1.0.0"
        id="jquery-3-js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js?ver=1.0.0"
        id="slider-js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="/storage/js/theme.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/10r9rmg7189fsvxw5q5dxjr7mpvzvpyv2beerpair7b0m4fa/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
        });
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#myeditorinstance',
            setup: function(editor) {
                editor.on("init", function() {
                    setTimeout(function() {
                        // Delay to ensure the document is ready
                        var docBody = editor.getDoc().body;
                        if (docBody) {
                            docBody.style.backgroundColor = "#1a1c1e";
                            docBody.style.color = "#fff";
                            docBody.style.padding = "20px";
                        } else {
                            console.error("Document body not found.");
                        }
                    }, 100);
                });
            },
            plugins: 'code table lists',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
        });
    </script>
    @vite('resources/js/app.js')
    @stack('js')
</body>

</html>
