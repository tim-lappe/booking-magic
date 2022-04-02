#!/bin/bash

buildPath="booking-magic"
srcPath=".."

cd ..
composer install || exit
npm install || exit
npm run build || exit

cd bin || exit

rm -r $buildPath

mkdir $buildPath
mkdir $buildPath/docs
mkdir $buildPath/lang
mkdir $buildPath/assets
mkdir $buildPath/src
mkdir $buildPath/templates
mkdir $buildPath/vendor

cp -r $srcPath/docs $buildPath
cp -r $srcPath/assets $buildPath
cp -r $srcPath/lang $buildPath
cp -r $srcPath/src $buildPath
cp -r $srcPath/templates $buildPath
cp -r $srcPath/vendor $buildPath
cp  $srcPath/booking-magic.php $buildPath
cp  $srcPath/config.php $buildPath
cp  $srcPath/constants.php $buildPath
cp  $srcPath/dependency.php $buildPath
cp  $srcPath/README.md $buildPath
cp  $srcPath/startup.php $buildPath

zip -r booking-magic.zip $buildPath

rm -r $buildPath

echo "Package created successfully!"