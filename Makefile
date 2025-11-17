.PHONY: help

NGINX_CONTAINER = bssc_admin_nginx
PHP_CONTAINER = bssc_admin_php
NGINX_CONTAINER_PROD = bssc_admin_nginx_prod
PHP_CONTAINER_PROD = bssc_admin_php_prod
REQUIREMENTS = docker docker-compose vi npm node git

# Check if required commands are available
check:
	$(foreach REQUIREMENT, $(REQUIREMENTS), \
		$(if $(shell command -v $(REQUIREMENT) 2> /dev/null), \
			$(info `$(REQUIREMENT)` has been found. OK!), \
			$(error Please install `$(REQUIREMENT)` before running setup.) \
		) \
	)

# Ensure Git considers the directory safe
setup-git-safe:
	@echo "Configuring Git to trust the repository directory..."
	@git config --global --add safe.directory /var/www/bssc_backend_admin

# Setup the environment and application
setup: check setup-git-safe
	@cp .env.example .env || true
	@vi .env
	@docker-compose -f compose.dev.yml up -d --build

	@echo "Waiting for containers to be ready..."
	@sleep 5

	# Retry logic to wait for PHP container to be up
	@until docker exec $(PHP_CONTAINER) php -v > /dev/null 2>&1; do \
		echo "Waiting for PHP container ($(PHP_CONTAINER)) to be ready..."; \
		sleep 2; \
	done

	@docker exec $(PHP_CONTAINER) chown -R www-data:www-data /var/www/bssc_backend_admin/storage /var/www/bssc_backend_admin/bootstrap/cache
	@docker exec $(PHP_CONTAINER) chmod -R 775 /var/www/bssc_backend_admin/storage /var/www/bssc_backend_admin/bootstrap/cache

	@docker exec $(PHP_CONTAINER) composer install --prefer-dist --no-interaction
	@docker exec $(PHP_CONTAINER) php artisan key:generate
	@docker exec $(PHP_CONTAINER) php artisan storage:link
#	@$(MAKE) setup-tables
#	@$(MAKE) clear-cache

# Clear various Laravel caches and fix permissions
clear-cache:
	@docker exec $(PHP_CONTAINER) chown -R www-data:www-data /var/www/bssc_backend_admin/storage /var/www/bssc_backend_admin/bootstrap/cache
	@docker exec $(PHP_CONTAINER) chmod -R 775 /var/www/bssc_backend_admin/storage /var/www/bssc_backend_admin/bootstrap/cache
	@docker exec $(PHP_CONTAINER) php artisan optimize:clear
	@docker exec $(PHP_CONTAINER) php artisan optimize
	@docker exec $(PHP_CONTAINER) php artisan cache:clear
	@docker exec $(PHP_CONTAINER) php artisan config:clear
	@docker exec $(PHP_CONTAINER) php artisan route:clear
	@docker exec $(PHP_CONTAINER) php artisan view:clear

# Run Laravel migrations
migrate:
	@docker exec $(PHP_CONTAINER) php artisan migrate:all $(ARGS)

# Run Laravel migrations for production
migrate-prod:
	@docker exec $(PHP_CONTAINER_PROD) php artisan migrate:fresh --force

# Seed the database
seed:
	@docker exec $(PHP_CONTAINER) php artisan db:seed --class=DatabaseSeeder

# Rollback N steps of migrations (default: 1)
migrate-revert:
	@docker exec $(PHP_CONTAINER) php artisan migrate:all --revert --steps=$${STEPS:-1} $(ARGS)

# Rebuild the dev environment
update-setup:
	@docker-compose up -d --build

# Production setup
setup-prod: check
	@cp env.production .env || true
	@vi .env
	@docker-compose -f docker-compose.prod.yml up -d --build

	@echo "Waiting for containers to be ready..."
	@sleep 10

	# Wait for all MySQL containers to be ready
	@echo "Waiting for MySQL containers to be ready..."
	@until docker exec pms_mysql_prod mysqladmin ping -h localhost -u root -ppassword --silent; do \
		echo "Waiting for main MySQL container to be ready..."; \
		sleep 3; \
	done
	@until docker exec pms_mysql_training_prod mysqladmin ping -h localhost -u root -ppassword --silent; do \
		echo "Waiting for training MySQL container to be ready..."; \
		sleep 3; \
	done
	@until docker exec pms_mysql_testing_prod mysqladmin ping -h localhost -u root -ppassword --silent; do \
		echo "Waiting for testing MySQL container to be ready..."; \
		sleep 3; \
	done

	# Wait for PHP container to be ready
	@echo "Waiting for PHP container to be ready..."
	@until docker exec $(PHP_CONTAINER_PROD) php -v > /dev/null 2>&1; do \
		echo "Waiting for PHP container to be ready..."; \
		sleep 2; \
	done

	# Set proper permissions
	@echo "Setting proper permissions..."
	@docker exec $(PHP_CONTAINER_PROD) chown -R www-data:www-data /var/www/pms/storage /var/www/pms/bootstrap/cache
	@docker exec $(PHP_CONTAINER_PROD) chmod -R 775 /var/www/pms/storage /var/www/pms/bootstrap/cache

	# Install dependencies
	@echo "Installing PHP dependencies..."
	@docker exec $(PHP_CONTAINER_PROD) composer install --prefer-dist --no-dev --no-interaction --optimize-autoloader

	# Generate application key
	@echo "Generating application key..."
	@docker exec $(PHP_CONTAINER_PROD) php artisan key:generate --force

	# Clear configuration cache to pick up new DB_HOST
	@docker exec $(PHP_CONTAINER_PROD) php artisan config:clear

	# Run migrations on all databases
	@echo "Running database migrations..."
	@docker exec $(PHP_CONTAINER_PROD) php artisan migrate:all --force

	# Optimize for production
	@echo "Optimizing for production..."
	@docker exec $(PHP_CONTAINER_PROD) php artisan optimize
	@docker exec $(PHP_CONTAINER_PROD) php artisan config:cache
	@docker exec $(PHP_CONTAINER_PROD) php artisan route:cache
	@docker exec $(PHP_CONTAINER_PROD) php artisan view:cache

	@echo "Production setup completed successfully!"
	@echo "Application should be available at: http://localhost"
	@echo "Health check: http://localhost/health"

# Production update
update-prod:
	@docker-compose -f docker-compose.prod.yml up -d --build

# Production health check
health-prod:
	@echo "Checking production containers status..."
	@docker-compose -f docker-compose.prod.yml ps
	@echo ""
	@echo "Testing application health..."
	@curl -f http://localhost/health || echo "Health check failed"
	@echo ""
	@echo "Testing database connection..."
	@docker exec $(PHP_CONTAINER_PROD) php /var/www/pms/docker/php/test-db.php

# View production logs
logs-prod:
	@docker-compose -f docker-compose.prod.yml logs -f

# View specific service logs
logs-php-prod:
	@docker-compose -f docker-compose.prod.yml logs -f php

logs-nginx-prod:
	@docker-compose -f docker-compose.prod.yml logs -f nginx

logs-mysql-prod:
	@docker-compose -f docker-compose.prod.yml logs -f mysql

# Production troubleshooting
troubleshoot-prod:
	@echo "=== Production Troubleshooting ==="
	@echo "1. Container Status:"
	@docker-compose -f docker-compose.prod.yml ps
	@echo ""
	@echo "2. Network Configuration:"
	@docker network inspect property-management-system-backend_pms_network --format='{{range .Containers}}{{.Name}}: {{.IPv4Address}}{{"\n"}}{{end}}'
	@echo ""
	@echo "3. Laravel Logs (last 10 lines):"
	@docker exec $(PHP_CONTAINER_PROD) tail -10 /var/www/pms/storage/logs/laravel.log || echo "No logs found"
	@echo ""
	@echo "4. Database Connection Test:"
	@docker exec $(PHP_CONTAINER_PROD) php /var/www/pms/docker/php/test-db.php
	@echo ""
	@echo "5. Application Health:"
	@curl -f http://localhost/health > /dev/null 2>&1 && echo "Application: OK" || echo "Application: FAILED"

# Quick production restart
restart-prod:
	@docker-compose -f docker-compose.prod.yml restart

# Production bash access
bash-prod:
	@docker exec -it $(PHP_CONTAINER_PROD) bash

# Production database access
db-prod:
	@docker exec -it pms_mysql_prod mysql -u root -ppassword pms

# Production training database access
db-training-prod:
	@docker exec -it pms_mysql_training_prod mysql -u root -ppassword pms_training

# Production testing database access
db-testing-prod:
	@docker exec -it pms_mysql_testing_prod mysql -u root -ppassword pms_testing

# Stop and remove containers
remove-setup:
	@docker-compose down

# Stop and remove production containers
remove-prod:
	@docker-compose -f docker-compose.prod.yml down

# Fresh migration with seed
setup-tables:
	@docker exec $(PHP_CONTAINER) php artisan migrate:all --fresh --seed $(ARGS)

# Fresh migration with seed for production
setup-tables-prod:
	@docker exec $(PHP_CONTAINER_PROD) php artisan migrate:all --fresh --seed --force

# Open bash shell inside PHP container
bash:
	@docker exec -it $(PHP_CONTAINER) bash
