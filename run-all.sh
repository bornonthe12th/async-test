#!/bin/bash

set -e

#VERSIONS=("php73" "php82" "php83")
#VERSIONS=("php83")
#VERSIONS=("php82")
VERSIONS=("php73")

echo "🚀 Running async test for PHP versions: ${VERSIONS[*]}"
echo

for version in "${VERSIONS[@]}"; do
  version_suffix=$(echo "$version" | sed 's/php//')
  test_file="test-async-${version_suffix}.php"
  test_path="$version/$test_file"

  if [[ ! -f "$test_path" ]]; then
    echo "❌ Test file '$test_path' not found. Skipping $version."
    continue
  fi

  echo "🔧 Building $version..."
  docker-compose build "$version" > /dev/null

  #echo "📦 Installing dependencies for $version..."
  docker-compose run --rm "$version" composer install > /dev/null

  echo "▶️ Running $test_file for $version..."
  docker-compose run --rm "$version" php "$test_file"

  echo
done

echo "✅ All versions completed."

