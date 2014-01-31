#Karma

New generation of PHP frameworks



[![Latest Stable Version](https://poser.pugx.org/thinframe/karma/v/stable.png)](https://packagist.org/packages/thinframe/karma)
[![Latest Unstable Version](https://poser.pugx.org/thinframe/karma/v/unstable.png)](https://packagist.org/packages/thinframe/karma)
[![License](https://poser.pugx.org/thinframe/karma/license.png)](https://packagist.org/packages/thinframe/karma)


##What is the problem with the current generation?



To explain the current problem let me start with a simple description on how PHP websites work:



1. A user makes a request from his browser to a certain website that is running using PHP

2. The HTTP server accepts that request and handles it in different ways:

    * Apache executes the PHP script associated with the requested path directly and returns the output

    * Nginx uses PHP-FPM to do basically the same thing.

3. The HTTP server sends the response back to the client





All good so far, but let's look a little bit deeper. When a PHP script is executed the followings things take place:



1. The necessary dependencies are loaded, lazy or all at once, and all the components are configured/initialized.

2. Your logic is executed depending on the request and a response is returned.



But there is a problem in this process, because one of those two steps is executed every single time. Oh well ... 

Step 1 is executed for every single request, and every PHP developer knows that this is the bottleneck of any PHP application, because IO operations are very expensive. 



##Solutions that already exists



* Mr Fabien Potencier, the arhitect behind Symfony2, the flag ship of modern PHP frameworks, tells us to cache the code, convert it to something like byte code. That is a GREAT idea, but doesn't it complicate things ? You will have a big component that will cache the actual code, that means extra code to support, and also this kind of caching complicates things

in a development process. So is this an overall solution ? 



* Others are investing in hardware. This is a good solution if you have the MONEY.

* And so on ... 





##A new aproach ... ?



Tom & David Kelley, the authors of the book `Creative Confidence` and owners of the company `IDEO`, say in their book that the best solution to an existing problem is to rethink the whole process that causes that problem. But the problem in this case is by design. PHP was made this way, right ? 



## The solution



Some time ago, I came across `ReactPHP`, it is a PHP library that offers Event-driven, non-blocking I/O. It is inspired from NodeJS and it is awesome. I played with it, I did some tests and then an idea came to my mind:



Instead of using two programs to do one thing, the HTTP server and the PHP, why don't we use a single one that will act as both? 



Basically, we merge the HTTP server, and the PHP framework that is behind the server, to make a single program written in PHP that will do both tasks. This way we will eliminate the recurrent loading/initialization time for PHP dependencies and also save some time that is lost between the HTTP server and PHP.



Painfull ? Not at all. 



##Karma

It is a ` PHP Application ` that acts as an HTTP server and a PHP framework, all at the same time. It provides an easy to use abstraction layer over anything that relates to socket work, so developers can focus on their work. It is fast, with a response time that is 80 to 90 percent faster than a conventional framework and it works with already existing PHP components, like Twig. 



It provides built-in routing using `Symfony Routing` component and all the architecture is build around `Symfony Dependency Injection Container`. Most of the socket work is handled by the `ReactPHP` component. Basically, it is built with already existing PHP components. 



##Installation



    composer create-project thinframe/karma-project <project_name> --stability=dev



If you want to use the command line component with completion support execute the following command in your project root:



    bash bin/thinframe-installer



From this point on, you can use the `thinframe` command in each karma project folder that you have. Otherwise, you can use `bin/thinframe` instead of `thinframe`.



##Usage



To see all available commands execute the following command: `thinframe help`. Pretty simple.



      help                   - Show this list

      server run             - Start the HTTP server

      server run --daemon    - Start the HTTP server as a daemon

      server stop            - Stop the HTTP server

      server status          - Check HTTP server status

      server restart         - Restart the HTTP server

      server monitor         - Restart the HTTP server when source files are changed

      debug routes           - Show all routes

      debug applications     - Show all loaded applications



The test the setup, run `thinframe server start` and in your browser go to `http://localhost:1337`. If you see a default Karma page, then everything works.



By default, when you install `karma-project`, it creates a bootstrap project. All project related files are located in `src/Acme/DemoApp`.



[to be continued ...]



##TODO's



* Write unit tests and functional tests

* Optimize memory

* More documentation

* Implement PsySH

* More logging

* All kinds of features

* Test on all Linux distributions.



##OS Support



So far it was tested on Ubuntu and Elementary OS. It should work on other Linux distributions pretty well.



##Dependencies



* PHP >= 5.4

* ext-inotify - http://www.php.net/manual/en/inotify.install.php



##Fair warning



This project is experimental. I recommend that it shouldn't be used on a production environment (yet). 

