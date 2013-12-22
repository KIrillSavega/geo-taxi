#!/bin/bash
# sphinx indexer script
############################
/usr/local/bin/indexer products_delta  --rotate --quiet --noprogress
/usr/local/bin/indexer --merge products products_delta --rotate --quiet --noprogress
/usr/local/bin/indexer --merge-killlists products  --rotate --quiet --noprogress
/usr/local/bin/indexer customer_delta  --rotate --quiet --noprogress
/usr/local/bin/indexer --merge customer customer_delta --rotate --quiet --noprogress
/usr/local/bin/indexer --merge-killlists customer  --rotate --quiet --noprogress




