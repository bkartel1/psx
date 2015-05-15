<?php
/*
 * PSX is a open source PHP framework to develop RESTful APIs.
 * For the current version and informations visit <http://phpsx.org>
 *
 * Copyright 2010-2015 Christoph Kappestein <k42b3.x@gmail.com>
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\Console\Schema;

use PSX\Test\ControllerTestCase;
use PSX\Test\Environment;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * JsonSchemaCommandTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    http://phpsx.org
 */
class JsonSchemaCommandTest extends ControllerTestCase
{
	public function testCommand()
	{
		$command = new JsonSchemaCommand(Environment::getService('config'), Environment::getService('resource_listing'));

		$commandTester = new CommandTester($command);
		$commandTester->execute(array(
			'path' => '/api'
		));

		$expect = <<<'JSON'
{
    "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
    "id": "urn:schema.phpsx.org#",
    "type": "object",
    "definitions": {
        "ref993f4bb37f524889fc963fedd6381458": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "userId": {
                    "type": "integer"
                },
                "title": {
                    "type": "string",
                    "minLength": 3,
                    "maxLength": 16,
                    "pattern": "[A-z]+"
                },
                "date": {
                    "type": "string"
                }
            },
            "additionalProperties": false
        }
    },
    "properties": {
        "getResponse": {
            "type": "object",
            "properties": {
                "entry": {
                    "type": "array",
                    "items": {
                        "$ref": "#\/definitions\/ref993f4bb37f524889fc963fedd6381458"
                    }
                }
            },
            "additionalProperties": false
        },
        "postRequest": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "userId": {
                    "type": "integer"
                },
                "title": {
                    "type": "string",
                    "minLength": 3,
                    "maxLength": 16,
                    "pattern": "[A-z]+"
                },
                "date": {
                    "type": "string"
                }
            },
            "required": [
                "title",
                "date"
            ],
            "additionalProperties": false
        },
        "postResponse": {
            "type": "object",
            "properties": {
                "success": {
                    "type": "boolean"
                },
                "message": {
                    "type": "string"
                }
            },
            "additionalProperties": false
        },
        "putRequest": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "userId": {
                    "type": "integer"
                },
                "title": {
                    "type": "string",
                    "minLength": 3,
                    "maxLength": 16,
                    "pattern": "[A-z]+"
                },
                "date": {
                    "type": "string"
                }
            },
            "required": [
                "id"
            ],
            "additionalProperties": false
        },
        "putResponse": {
            "type": "object",
            "properties": {
                "success": {
                    "type": "boolean"
                },
                "message": {
                    "type": "string"
                }
            },
            "additionalProperties": false
        },
        "deleteRequest": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer"
                },
                "userId": {
                    "type": "integer"
                },
                "title": {
                    "type": "string",
                    "minLength": 3,
                    "maxLength": 16,
                    "pattern": "[A-z]+"
                },
                "date": {
                    "type": "string"
                }
            },
            "required": [
                "id"
            ],
            "additionalProperties": false
        },
        "deleteResponse": {
            "type": "object",
            "properties": {
                "success": {
                    "type": "boolean"
                },
                "message": {
                    "type": "string"
                }
            },
            "additionalProperties": false
        }
    }
}
JSON;

		$this->assertJsonStringEqualsJsonString($expect, $commandTester->getDisplay());
	}

	public function testCommandAvailable()
	{
		$command = Environment::getService('console')->find('schema:jsonschema');

		$this->assertInstanceOf('PSX\Console\Schema\JsonSchemaCommand', $command);
	}

	protected function getPaths()
	{
		return [
			[['GET', 'POST', 'PUT', 'DELETE'], '/api', 'PSX\Controller\Foo\Application\SchemaApi\VersionViewController']
		];
	}
}
