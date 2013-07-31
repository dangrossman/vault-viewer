### Spreedly UI: A lightweight frontend for the Spreedly Core API

![Spreedly UI](http://i.imgur.com/gipxlf5.png)

Spreedly UI is a PHP application for listing, searching and redacting payment gateways, payment 
methods and transactions in a [Spreedly Core](http://www.spreedly.com) account. It also serves as 
an example of using the included Spreedly PHP class that wraps common Spreedly Core API usage.

**[View a demo](http://spreedly.awio.com/)**

## Installation

Clone or unzip this repository to the document root of a web server configured to serve PHP, 
such as Apache with mod_php or nginx with php-fpm. PHP 5.3 or newer is required.

There are references to absolute paths/URIs in the HTML, so don't place the code in a subfolder 
of a domain.

All non-static requests should be directed to `index.php`. A `.htaccess` file that does 
this for Apache is included.

Upon loading the site in your browser, you'll see a login box asking for your Spreedly 
environment key and access secret. You can also hardcode these in the `index.php` file to
skip the login screen.

It is highly recommended you only run this in a secure environment, since the application will
access and display the cardholder data you have stored in your Spreedly account. 

## License

Copyright (c) 2013 Dan Grossman. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the author nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
