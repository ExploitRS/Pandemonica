<div align="center">
    <p align="center">
        <a href="https://helltaker.fandom.com/wiki/Pandemonica">
            <img src="https://github-production-user-asset-6210df.s3.amazonaws.com/85566220/294228806-199a2c22-d975-4e5c-9755-582e4a9c2f57.png" width="500" alt="Pandemonica portrait">
        </a>
    </p>
    <h1>Pandemonica</h1>
    <br/>
    <p>Pandemonica is an API server for a simple ToDo application.</p>
    <br/>
</div>

## Name

Pandemonica is the name of the customer service representative in [Hell](https://helltaker.fandom.com/wiki/Helltaker_Wiki).

## Run

Clone the repo

```bash
git clone https://ExploitRS/Pandemonica
```

And move to the root directory of `Pandemonica`

```bash
cd Pandemonica
```

Copy the `.env.example` file to `.env`

```bash
cp .env.example .env
```

Install dependencies

```bash
composer i
```

To run the server

```bash
./vendor/bin/sail up
```

> [!NOTE]
> After starting the server with sail, it is necessary to prepare the DB.

> ```bash
> ./vendor/bin/sail artisan migrate
> ```

## Development

### Test

To run the tests

```bash
./vendor/bin/sail artisan test
```

To run tests for a specific feature

```bash
// Run tests for the Tasks feature
./vendor/bin/sail artisan test --filter TasksTest

// Run tests for the Categories feature
./vendor/bin/sail artisan test --filter CategoriesTest
```

### API endpoints

To display the valid API endpoints

```bash
./vendor/bin/sail artisan route:list -v
```

## References

- [laratube](https://github.com/miladev95/laratube)

## License

[MIT](https://github.com/ExploitRS/Pandemonica/blob/main/LICENSE)