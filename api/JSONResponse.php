<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Utility class for building and sending JSON responses to client
 */
class JSONResponse {

    private int $responseCode;
    private mixed $responseData;
    private ?string $responseMessage;
    private ?array $responseErrors;
    private int $responseCount;

    /**
     * Builds response to send to client
     *
     * @param int         $responseCode    response code to send
     * @param mixed       $responseData    for any api data to send objects ect. will be converted to json
     * @param string|null $responseMessage a general message to send to client
     * @param array|null  $responseErrors  any error messages to send to client
     * @param int         $responseCount   count of records pulled.
     */
    public function __construct(int $responseCode = 200, $responseData = NULL, ?string $responseMessage = NULL, array $responseErrors = NULL, int $responseCount = 0) {
        $this->responseCode = $responseCode;
        $this->responseData = $responseData;
        $this->responseMessage = $responseMessage;
        $this->responseErrors = $responseErrors;
        $this->responseCount = $responseCount;
    }

    /**
     * Sends response back to client
     *
     * @return void
     */
    public function respond(): void {
        self::respondStatic($this->responseCode, $this->responseData, $this->responseMessage, $this->responseErrors, $this->responseCount);
    }

    /**
     * Sends response back to client and ends script execution
     *
     * @return void
     */
    public function respondAndExit(): void {
        self::respondAndExitStatic($this->responseCode, $this->responseData, $this->responseMessage, $this->responseErrors, $this->responseCount);
    }

    /**
     * Sends response back to client
     *
     * @param int         $responseCode    response code to send
     * @param mixed       $responseData    for any api data to send objects ect. will be converted to json
     * @param string|null $responseMessage a general message to send to client
     * @param array|null  $responseErrors  any error messages to send to client
     * @param int         $responseCount   count of records retrieved
     *
     * @return void
     */
    public static function respondStatic(int $responseCode = 200, $responseData = NULL, ?string $responseMessage = NULL, array $responseErrors = NULL, int $responseCount = 0): void {
        header(http_response_code($responseCode));
        $response = [];
        if(isset($responseMessage)) $response["msg"] = $responseMessage;
        if(isset($responseData)) $response["data"] = $responseData;
        if(isset($responseErrors)) $response["err"] = $responseErrors;
        if(isset($responseCount)) $response["count"] = $responseCount;
        if(!empty($response)) echo json_encode($response);
    }

    /**
     * Sends response back to client and ends script execution
     *
     * @param int         $responseCode    response code to send
     * @param mixed       $responseData    for any api data to send objects ect. will be converted to json
     * @param string|null $responseMessage a general message to send to client
     * @param array|null  $responseErrors  any error messages to send to client
     * @param int         $responseCount   count of records retrieved
     *
     * @return void
     */
    #[NoReturn] public static function respondAndExitStatic(int $responseCode = 200, $responseData = NULL, ?string $responseMessage = NULL, array $responseErrors = NULL, int $responseCount = 0): void {
        self::respondStatic($responseCode, $responseData, $responseMessage, $responseErrors, $responseCount);
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
     * @return mixed
     */
    public function getResponseData(): mixed
    {
        return $this->responseData;
    }

    /**
     * @param mixed $responseData
     */
    public function setResponseData($responseData): void {
        $this->responseData = $responseData;
    }

    /**
     * @return string|null
     */
    public function getResponseMessage(): ?string {
        return $this->responseMessage;
    }

    /**
     * @param string|null $responseMessage
     */
    public function setResponseMessage(?string $responseMessage): void {
        $this->responseMessage = $responseMessage;
    }

    /**
     * @return array|null
     */
    public function getResponseErrors(): ?array {
        return $this->responseErrors;
    }

    /**
     * @param array|null $responseErrors
     */
    public function setResponseErrors(?array $responseErrors): void {
        $this->responseErrors = $responseErrors;
    }
}
