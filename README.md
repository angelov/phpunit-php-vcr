# PHP-VCR integration for PHPUnit

A library that allows you to easily use the PHP-VCR library in your PHPUnit tests.

## Requirements

* PHP 8.2+
* PHPUnit 10+

## Installation

```
composer require --dev angelov/phpunit-php-vcr
```

Then, add the extension to your PHPUnit configuration file.

(All parameters are optional.)

```xml
    <extensions>
        <bootstrap class="\Angelov\PHPUnitPHPVcr\Extension">
            <parameter name="cassettesPath" value="tests/fixtures" />
            <parameter name="storage" value="yaml" />                                   <!-- https://php-vcr.github.io/documentation/configuration/#storage -->
            <parameter name="libraryHooks" value="stream_wrapper, curl, soap" />        <!-- https://php-vcr.github.io/documentation/configuration/#library-hooks -->
            <parameter name="requestMatchers" value="method, url, query_string, ..." /> <!-- https://php-vcr.github.io/documentation/configuration/#request-matching -->
            <parameter name="whitelistedPaths" value="" />                              <!-- https://php-vcr.github.io/documentation/configuration/#white--and-blacklisting-paths -->
            <parameter name="blacklistedPaths" value="" />                              <!-- https://php-vcr.github.io/documentation/configuration/#white--and-blacklisting-paths -->
            <parameter name="mode" value="new_episodes" />                              <!-- https://php-vcr.github.io/documentation/configuration/#record-modes -->
        </bootstrap>
    </extensions>
```

## Usage

The library provides an `UseCassette` attribute that can be declared on test classes or specific test methods. The 
attribute accepts a cassette name and optional parameters for advanced functionality like separate cassettes per 
data provider case.

When running the tests, the library will automatically turn the recorder on and off, and insert the cassettes when 
needed.

**Examples:**

* When declared on a class, PHP-VCR will intercept the requests in all test methods in that class, and will store the 
responses in the given cassette.

    ```php
    use Angelov\PHPUnitPHPVcr\UseCassette;
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\TestCase;

    #[UseCassette("example_cassette.yml")]
    class ExampleTest extends TestCase
    {
        #[Test]
        public function example(): void { ... }

        #[Test]
        public function another(): void { ... }
    }
    ```

* When declared on a test method, only requests in that methods will be intercepted and stored in the given cassette. 
Note that it can be declared on multiple test methods with different cassettes.

    ```php
    use Angelov\PHPUnitPHPVcr\UseCassette;
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\TestCase;

    class ExampleTest extends TestCase
    {
        #[Test]
        #[UseCassette("example.yml")]
        public function example(): void { ... }

        #[Test]
        public function another(): void { ... }

        #[Test]
        #[UseCassette("example_2.yml")]
        public function recorded(): void { ... }
    }
    ```

* When declared both on the class and on a specific method, the name from the attribute declared on the method will be 
used for that method. In this example, the responses from the requests made in the `example()` method will be stored in 
`example.yml` and the ones from `recorded()` in `example_2.yml`.

    ```php
    use Angelov\PHPUnitPHPVcr\UseCassette;
    use PHPUnit\Framework\Attributes\Test;
    use PHPUnit\Framework\TestCase;

    #[UseCassette("example.yml")]
    class ExampleTest extends TestCase
    {
        #[Test]
        public function example(): void { ... }

        #[Test]
        #[UseCassette("example_2.yml")]
        public function recorded(): void { ... }
    }
    ```

## DataProvider Support

The library supports PHPUnit's `DataProvider` functionality with additional options for managing cassettes when using data providers.

### Basic DataProvider Usage

When using a data provider with the basic `UseCassette` attribute, all test cases from the data provider will share the same cassette file:

```php
use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    #[Test]
    #[UseCassette("shared_cassette.yml")]
    #[DataProvider("urls")]
    public function testWithDataProvider(string $url): void
    {
        $content = file_get_contents($url);
        // All test cases will use the same cassette file
    }

    public static function urls(): iterable
    {
        yield ["https://example.com"];
        yield ["https://example.org"];
    }
}
```

### Separate Cassettes Per DataProvider Case

For more granular control, you can create separate cassette files for each data provider case using the `separateCassettePerCase` parameter:

```php
use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    #[Test]
    #[UseCassette(name: "separate_cassettes.yml", separateCassettePerCase: true)]
    #[DataProvider("urls")]
    public function testWithSeparateCassettes(string $url): void
    {
        $content = file_get_contents($url);
        // Each test case will have its own cassette file:
        // - separate_cassettes-0.yml
        // - separate_cassettes-1.yml
    }

    public static function urls(): iterable
    {
        yield ["https://example.com"];
        yield ["https://example.org"];
    }
}
```

### Named DataProvider Cases

When using named data provider cases, the cassette files will use the case names:

```php
use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    #[Test]
    #[UseCassette(name: "named_cassettes.yml", separateCassettePerCase: true)]
    #[DataProvider("namedUrls")]
    public function testWithNamedCassettes(string $url): void
    {
        $content = file_get_contents($url);
        // Each test case will have its own cassette file:
        // - named_cassettes-example-com.yml
        // - named_cassettes-example-org.yml
    }

    public static function namedUrls(): iterable
    {
        yield 'example.com' => ["https://example.com"];
        yield 'example.org' => ["https://example.org"];
    }
}
```

### Grouping Cassettes in Directories

To organize separate cassette files in directories, use the `groupCaseFilesInDirectory` parameter:

```php
use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    #[Test]
    #[UseCassette(
        name: "organized_cassettes.yml",
        separateCassettePerCase: true,
        groupCaseFilesInDirectory: true
    )]
    #[DataProvider("urls")]
    public function testWithOrganizedCassettes(string $url): void
    {
        $content = file_get_contents($url);
        // Cassette files will be organized in a directory:
        // - organized_cassettes/0.yml
        // - organized_cassettes/1.yml
    }

    public static function urls(): iterable
    {
        yield ["https://example.com"];
        yield ["https://example.org"];
    }
}
```

### Class-Level DataProvider Support

The dataProvider functionality also works when the `UseCassette` attribute is declared at the class level:

```php
use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[UseCassette(name: "class_level.yml", separateCassettePerCase: true)]
class ExampleTest extends TestCase
{
    #[Test]
    #[DataProvider("urls")]
    public function testMethod(string $url): void
    {
        $content = file_get_contents($url);
        // Each test case will have separate cassettes:
        // - class_level-0.yml
        // - class_level-1.yml
    }

    public static function urls(): iterable
    {
        yield ["https://example.com"];
        yield ["https://example.org"];
    }
}
```
