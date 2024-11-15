mkdir -p .git/hooks
tee .git/hooks/pre-commit << EOF
#!/bin/bash

# IDE Helper
php artisan ide-helper:generate
php artisan ide-helper:eloquent
php artisan ide-helper:models -N

# PHP Lint
./vendor/bin/pint --repair
EOF

chmod +x .git/hooks/pre-commit
