# Async PHP Testing with Xdebug in Docker

This project demonstrates how to run and debug asynchronous PHP code using Docker, Docker Compose, and multiple PHP versions (7.3, 8.2, 8.3) with Xdebug.

---

## 🚧 Challenges Encountered

### 1. **PHP 8.x & Xdebug Configuration Changes**
- `xdebug.remote_host` is deprecated in Xdebug 3+. You now use `xdebug.client_host`.
- The configuration style and behavior differs slightly between PHP versions (e.g. 8.2 correctly picked up `xdebug.mode=debug`, while 8.3 defaulted to `develop` until explicitly overridden).

### 2. **Mac-Specific DNS Resolution Issues**
- On macOS, `host.docker.internal` **doesn't resolve** properly inside Linux-based Docker containers.
- This caused Xdebug to fail to connect to the IDE.

### ✅ Solution:
To fix this, we added the following to our `docker-compose.yml`:

```yaml
extra_hosts:
  - "host.docker.internal:host-gateway"
```

This maps `host.docker.internal` inside the container to the gateway IP address of the host machine, enabling the container to connect to services on the host (e.g. PhpStorm debugger).

---

## 🐳 Docker Setup Summary
Each PHP version has its own Dockerfile. We:
1. Installed build tools + Xdebug.
2. Explicitly enabled Xdebug.
3. Set the following config:
   ```ini
   xdebug.mode=debug
   xdebug.start_with_request=yes
   xdebug.client_host=host.docker.internal
   xdebug.client_port=9003
   xdebug.log=/tmp/xdebug.log
   ```

We used `host.docker.internal` because PhpStorm is running on the host, and Xdebug needs to call back to it.

---

## ▶️ Running the Tests

Use the script:
```bash
./run-all.sh
```

Or manually run for each PHP version:
```bash
docker-compose run --rm php73 php test-async-73.php
docker-compose run --rm php82 php test-async-82.php
docker-compose run --rm php83 php test-async-83.php
```

> You **no longer need** `-dxdebug.remote_host=...`.
> If you've set `xdebug.client_host` in your config, you're good.

---

## 🐛 Debugging in PhpStorm

### 1. Go to **Settings > PHP > Servers**
- Add a server called `async-server`
- Host: `localhost`
- Port: `80`
- Use path mappings:
    - Map each `/app` folder to your project folder (e.g. `/Users/you/PhpstormProjects/async-test`)

### 2. Go to **Settings > PHP > CLI Interpreters**
- Add Docker Compose-based interpreters for each version:
    - Configuration file: `docker-compose.yml`
    - Service: `php73`, `php82`, `php83`

### 3. Go to **Run > Edit Configurations**
- Create a new **PHP Script** configuration
- Choose the correct interpreter (e.g. php82)
- Script: `test-async.php`

Start a debug session in PhpStorm and run your script via Docker.

---

## 🧼 Cleanup
After testing:
```bash
docker-compose down --volumes --remove-orphans
```

---

## 💡 Tip
Rename test files when switching between versions (`test-async-php82.php`) to avoid PhpStorm locking breakpoints to one specific config.
