# Pandemonica

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