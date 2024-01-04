<div align="center">
    <p align="center">
        <a href="https://helltaker.fandom.com/wiki/Pandemonica">
            <img src="https://cdn.donmai.us/original/b9/a1/__pandemonica_helltaker_drawn_by_sillyzer0__b9a135a14d1a049a94f92ea7ea5b7406.png" width="400" alt="Pandemonica portrait">
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

## API Endpoints

### check by artisan command in project's root directory

```bash
./vendor/bin/sail artisan route:list -v
```

## References

- [laratube](https://github.com/miladev95/laratube)