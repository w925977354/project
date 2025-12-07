#!/bin/bash

# HTTPS Setup Script for AWS Ubuntu
# This script configures HTTPS using self-signed certificate (for IP-based access)

echo "========================================="
echo "HTTPS Configuration"
echo "========================================="
echo ""

# Check if running as non-root
if [ "$EUID" -eq 0 ]; then 
    echo "✗ Please do not run this script as root"
    exit 1
fi

# Get server IP
SERVER_IP=$(curl -s ifconfig.me)
echo "→ Detected server IP: $SERVER_IP"
echo ""

# Option 1: Self-signed certificate (works with IP)
echo "Configuring Self-Signed SSL Certificate..."
echo ""

# Create SSL directory if it doesn't exist
sudo mkdir -p /etc/ssl/private
sudo mkdir -p /etc/ssl/certs

# Generate self-signed certificate
echo "→ Generating SSL certificate..."
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/photo-gallery-selfsigned.key \
    -out /etc/ssl/certs/photo-gallery-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=PhotoGallery/CN=${SERVER_IP}"

echo "✓ SSL certificate generated"
echo ""

# Create SSL virtual host configuration
echo "→ Creating SSL virtual host..."
sudo tee /etc/apache2/sites-available/photo-gallery-ssl.conf > /dev/null <<EOF
<VirtualHost *:443>
    ServerName ${SERVER_IP}
    DocumentRoot /var/www/photo-gallery/public
    
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/photo-gallery-selfsigned.crt
    SSLCertificateKeyFile /etc/ssl/private/photo-gallery-selfsigned.key
    
    <Directory /var/www/photo-gallery/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/photo-gallery-ssl-error.log
    CustomLog \${APACHE_LOG_DIR}/photo-gallery-ssl-access.log combined
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName ${SERVER_IP}
    Redirect permanent / https://${SERVER_IP}/
</VirtualHost>
EOF

echo "✓ SSL virtual host created"
echo ""

# Enable SSL module and site
echo "→ Enabling SSL module..."
sudo a2enmod ssl
sudo a2enmod headers

echo "→ Enabling SSL site..."
sudo a2ensite photo-gallery-ssl.conf

# Disable non-SSL site
sudo a2dissite photo-gallery.conf 2>/dev/null || true

# Test Apache configuration
echo "→ Testing Apache configuration..."
sudo apache2ctl configtest

# Restart Apache
echo "→ Restarting Apache..."
sudo systemctl restart apache2

echo ""
echo "========================================="
echo "✓ HTTPS Configuration Complete!"
echo "========================================="
echo ""
echo "Your application is now accessible at:"
echo "  HTTPS: https://${SERVER_IP}"
echo ""
echo "⚠️  NOTE: Since this is a self-signed certificate,"
echo "    browsers will show a security warning."
echo "    This is normal - click 'Advanced' and 'Proceed' to continue."
echo ""
echo "Certificate details:"
echo "  Certificate: /etc/ssl/certs/photo-gallery-selfsigned.crt"
echo "  Private Key: /etc/ssl/private/photo-gallery-selfsigned.key"
echo "  Valid for: 365 days"
echo ""
