# Use the official Nginx image as base
FROM nginx:1.25-alpine

# Set environment variables
ENV TZ=Asia/Manila

# Install necessary packages
RUN apk add --no-cache tzdata

# Copy Nginx configuration files
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create necessary directories
RUN mkdir -p /var/www/bssc_backend_admin

# Copy project files to container
COPY . /var/www/bssc_backend_admin

# Set proper permissions and ownership
RUN chown -R nginx:nginx /var/www/bssc_backend_admin && \
    chmod -R 755 /var/www/bssc_backend_admin && \
    chmod -R 777 /var/www/bssc_backend_admin/storage && \
    chmod -R 777 /var/www/bssc_backend_admin/bootstrap/cache

# Create storage symbolic link
RUN ln -sf /var/www/bssc_backend_admin/storage/app/public /var/www/bssc_backend_admin/public/storage

# Expose port 80
EXPOSE 80

# Start Nginx
CMD ["nginx", "-g", "daemon off;"] 