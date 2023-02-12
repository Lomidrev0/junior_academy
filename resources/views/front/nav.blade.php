<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header class="header-area">
    <div class="header-container">
        <div class="site-logo">
            <a href="/">Junior<span>Akadémia</span></a>v
        </div>
        <div class="mobile-nav">
            <i class="bi bi-list"></i>
        </div>
        <div class="site-nav-menu">
            <ul class="primary-menu">
                <li><a href="/" class="active">Home</a></li>
                <li><a href="/gallery">Galéria</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/login">Login</a></li>
            </ul>
        </div>
    </div>
</header>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $(".mobile-nav i").click(function(){
            $(".site-nav-menu").toggleClass("mobile-menu");
        });
    });
</script>

</body>
