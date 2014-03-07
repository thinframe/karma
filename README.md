#Karma
New generation of PHP frameworks

[![Latest Stable Version](https://poser.pugx.org/thinframe/karma/v/stable.png)](https://packagist.org/packages/thinframe/karma)
[![Latest Unstable Version](https://poser.pugx.org/thinframe/karma/v/unstable.png)](https://packagist.org/packages/thinframe/karma)
[![License](https://poser.pugx.org/thinframe/karma/license.png)](https://packagist.org/packages/thinframe/karma)

##What is the problem with the current generation?

To explain the current problem let me start with simple introduction on how PHP websites work:

1. A user makes a request from his browser to a certain website that is running using PHP
2. The HTTP server accept that request and handle it in different ways:
    * Apache executes directly the PHP script associated with the requested path and return the output
    * Nginx is using PHP-FPM to do basically the same thing.
3. The HTTP server sends the response back to the client


All good until know, but let look a little bit deeper. When a PHP script is executed the followings things take place:

1. The necesary dependencies are loaded, lazy or all at once, and all the components are configured/initialized.
2. Your logic is executed depending on the request and a response is returned.

But there is a problem in this process, because one of those two steps is executed every single time. Oh well ... 
step 1 is executed at every single request, and every PHP developer knows that this is the bottleneck of any PHP application, because IO operations are very expensive. 

##Solutions that already exists

* Mr Fabien Potencier, the arhitect behind Symfony2, the flag ship of modern PHP frameworks, tells us to cache the code, convert it to something like byte code. That is a GREAT idea, but doesn't it complicate the things ? You will have a big component that will cache the actual code, that means extra code to support, and also this kind of caching complicate things
in a development process. So it this an overall solution ? 

* Others, are investing in hardware. This is a good solution if you have the MONEY.
* And so on ... 


##A new aproach ... ?

Tom & David Kelley, the authors of the book `Creative Confidence` and of the company `IDEO`, are saying in their book that the best solution to an existing problem is to rethink the hole process that cause that problem. But the problem in this case is by design. PHP was made this way, right ? 

## The solution

Some time ago, I've came across `ReactPHP`, it is a PHP library that offers Event-driven, non-blocking I/O. It is inspired from NodeJS and it is awesome. I've played with it, I've did some tests and then an idea came to my mind:

Instead of using two programs to do one thing, the HTTP server and the PHP, why don't we use a single one that will act as both? 

Basically, we merge the HTTP server, and the PHP framework that is behind the server, to make a single program written in PHP that will do both tasks. This way we will eliminate the recurent loading/initialization time for PHP dependencies and also save some time that is lost between the HTTP server and PHP. That means working at a socket level in PHP without the help of build in helpers from PHP core that only work on a SAPI. 

Painfull ? Not at all. 

##Karma
It is a ` PHP Application ` that acts as a HTTP server and a PHP framework, all at the same time. It provides an easy to use abstraction layer over anything that relates to socket work, so the developers can focus on their work. It is fast, with a response time with 80 to 90 percent faster than a conventional framework and it works with already existing PHP components, like Twig. 

It provides build-in routing using `Symfony Routing` component and all the arhitecture is build arount `Symfony Dependency Injection Container`. Most of the socket work is handled by the `ReactPHP` component. Basically, it is build with already existing PHP components. 

[to be continued]

#Thanks

* For the PhpStorm license that JetBrains provided