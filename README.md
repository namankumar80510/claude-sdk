# Claude API PHP SDK

This is a simple but useful PHP Class that you can use to communicate with Anthropic's Claude via API.

## Installation

```bash
composer require dikki/claude-sdk
```

## Structure

claude-sdk/
├── src/
│   ├── Client/
│   │   ├── ClientInterface.php
│   │   ├── GuzzleClient.php
│   │   └── ResponseHandler.php
│   ├── Config/
│   │   ├── ClientConfig.php
│   │   └── ModelConfig.php
│   ├── Contracts/
│   │   ├── MessageInterface.php
│   │   └── ResponseInterface.php
│   ├── Enum/
│   │   ├── ModelEnum.php
│   │   └── RoleEnum.php
│   ├── Exception/
│   │   ├── ApiException.php
│   │   ├── ConfigurationException.php
│   │   └── ValidationException.php
│   ├── Message/
│   │   ├── Message.php
│   │   ├── MessageBuilder.php
│   │   └── MessageCollection.php
│   ├── Response/
│   │   ├── Response.php
│   │   └── StreamedResponse.php
│   ├── Validator/
│   │   └── RequestValidator.php
│   ├── Claude.php
│   └── ClaudeBuilder.php
├── tests/
├── composer.json
└── README.md