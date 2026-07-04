#!/bin/bash
find resources/views -type f -name "*.blade.php" -exec sed -i 's/document.addEventListener('\''turbo:load'\''/document.addEventListener('\''DOMContentLoaded'\''/g' {} +
find resources/views -type f -name "*.blade.php" -exec sed -i 's/document.removeEventListener('\''turbo:load'\''/document.removeEventListener('\''DOMContentLoaded'\''/g' {} +
find resources/views -type f -name "*.blade.php" -exec sed -i '/<meta name="turbo-cache-control" content="no-preview">/d' {} +
find resources/views -type f -name "*.blade.php" -exec sed -i 's/ data-turbo-track="reload"//g' {} +
find resources/views -type f -name "*.blade.php" -exec sed -i 's/ data-turbo="false"//g' {} +
find resources/views -type f -name "*.blade.php" -exec sed -i '/<script type="module" src="https:\/\/cdn.jsdelivr.net\/npm\/@hotwired\/turbo@8.0.4\/dist\/turbo.es2017-esm.js"><\/script>/d' {} +
find resources/views -type f -name "*.blade.php" -exec sed -i 's/ @turbo:submit-end=".*"//g' {} +
