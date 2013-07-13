#!/usr/bin/env bash

# Deploy destination stuff
ENV="test"
BASE_DIR="/var/www/network"
DEPL_FULL_PATH=$BASE_DIR/$ENV

# Jenkins build stuff
BUILD_PATH="$HUDSON_HOME/jobs/$PROMOTED_JOB_NAME/builds/$PROMOTED_NUMBER/artifact"
BUILD_FILE="binary-network-$PROMOTED_NUMBER.tar.gz"
BUILD_FULL_PATH="$BUILD_PATH/$BUILD_FILE"

# Clean up deploy dir
rm -rf $DEPL_FULL_PATH
mkdir $DEPL_FULL_PATH

# Copy build tar to deploy dir
cp $BUILD_FULL_PATH $DEPL_FULL_PATH

# Extract files and remove tar
cd $DEPL_FULL_PATH
tar -xvf $BUILD_FILE
rm $BUILD_FILE

# Set shitty permissions
chmod 777 -R public/assets app/runtime

# Copy composer dependencies from jenkins workspace
# Go back to workspace dir
cd -
cp -R vendor $DEPL_FULL_PATH
