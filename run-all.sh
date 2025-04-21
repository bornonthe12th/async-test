#!/bin/bash

set -e

VERSIONS=("php73" "php82" "php83")

echo "🚀 Running async test for PHP versions: ${VERSIONS[*]}"
echo

for version in "${VERSIONS[@]}"; do
  test_file="test-async-${version}.php"

  if [[ ! -f "$version/$test_file" ]]; then
    echo "❌ Test file '$version/$test_file' not found. Skipping $version."
    continue
  fi

  echo "🔧 Building $version..."
  docker-compose build "$version" > /dev/null

  echo "📦 Installing dependencies for $version..."
  docker-compose run --rm "$version" composer install > /dev/null

  echo "▶️ Running $test_file for $version..."
  docker-compose run --rm "$version" php "$test_file"

  echo
done

echo "✅ All versions completed."
