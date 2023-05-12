<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Utility class for building and sending Page responses to client
 */
class PageResponse {

    private int $responseCode;
    private ?string $responseHTML;

    /**
     * Builds response to send to client
     *
     * @param int         $responseCode    response code to send
     * @param string|null $responseHTML    html of page to display to client
     */
    public function __construct(int $responseCode = 200, string $responseHTML = NULL) {
        $this->responseCode = $responseCode;
        $this->responseHTML = $responseHTML;
    }

    /**
     * Sends response back to client
     *
     * @return void
     */
    public function respond(): void {
        self::respondStatic($this->responseCode, $this->responseHTML);
    }

    /**
     * Sends response back to client and ends script execution
     *
     * @return void
     */
    public function respondAndExit(): void {
        self::respondAndExitStatic($this->responseCode, $this->responseHTML);
    }

    /**
     * Sends response back to client
     *
     * @param int         $responseCode response code to send
     * @param string|null $responseHTML html of page to display to client
     *
     * @return void
     */
    public static function respondStatic(int $responseCode = 200, string $responseHTML = NULL): void {
        header(http_response_code($responseCode));
        echo $responseHTML;
    }

    /**
     * Sends response back to client and ends script execution
     *
     * @param int         $responseCode    response code to send
     * @param string|null $responseHTML    html of page to display to client
     *
     * @return void
     */
    #[NoReturn] public static function respondAndExitStatic(int $responseCode = 200, string $responseHTML = NULL): void {
        self::respondStatic($responseCode, $responseHTML);
        exit;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int {
        return $this->responseCode;
    }

    /**
     * @param int $responseCode
     */
    public function setResponseCode(int $responseCode): void {
        $this->responseCode = $responseCode;
    }

    /**
     * @return string|null
     */
    public function getResponseHTML(): ?string {
        return $this->responseHTML;
    }

    /**
     * @param string|null $responseHTML
     */
    public function setResponseHTML(?string $responseHTML): void {
        $this->responseHTML = $responseHTML;
    }

    /**
     * Returns a basic error page html with spot for message and list of errors
     *
     * @param int    $code
     * @param string $message
     * @param array  $errors
     *
     * @return string
     */
    public static function getErrorPage(int $code = 400, string $message = "An Error Has Occurred", array $errors = []): string {
        $errors = implode("", array_map(function($error) {
            return "<li>$error</li>";
        }, $errors));
        return <<<html
        <style>
        *{
            transition: all 0.6s;
        }
        body{
            font-family: "Lato", sans-serif;
            color: #888;
            margin: 0;
        }
        #main{
            display: table;
            width: 100%;
            height: 100vh;
            text-align: center;
        }
        .fof{
            display: table-cell;
            vertical-align: middle;
        }
        .fof h1{
            font-size: 50px;
        	display: inline-block;
        	padding-right: 12px;
        	animation: type .5s alternate infinite;
        }
        .fof ul {
            display: inline-block;
        }
        @keyframes type{
            from{box-shadow: inset -3px 0 0 #888;}
        	to{box-shadow: inset -3px 0 0 transparent;}
        }
        </style>
        <div id="main">
            	<div class="fof">
                		<h1>Error $code</h1>
                		<h3>$message</h3>
                		<ul>$errors</ul>
            	</div>
        </div>
        html;
    }
}

