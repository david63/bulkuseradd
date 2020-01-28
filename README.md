# Bulk User Add extension for phpBB

Adds the ability to add users from an Excel spreadsheet or a CSV file.

[![Build Status](https://travis-ci.com/david63/bulkuseradd.svg?branch=master)](https://travis-ci.com/david63/bulkuseradd)
[![License](https://poser.pugx.org/david63/bulkuseradd/license)](https://packagist.org/packages/david63/bulkuseradd)
[![Latest Stable Version](https://poser.pugx.org/david63/bulkuseradd/v/stable)](https://packagist.org/packages/david63/bulkuseradd)
[![Latest Unstable Version](https://poser.pugx.org/david63/bulkuseradd/v/unstable)](https://packagist.org/packages/david63/bulkuseradd)
[![Total Downloads](https://poser.pugx.org/david63/bulkuseradd/downloads)](https://packagist.org/packages/david63/bulkuseradd)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9f82f66a5d5b459491425de15a257cd2)](https://www.codacy.com/manual/david63/bulkuseradd?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=david63/bulkuseradd&amp;utm_campaign=Badge_Grade)

## Minimum Requirements
* phpBB 3.3.0
* PHP 7.1.3

## Install
1. [Download the latest release](https://github.com/david63/bulkuseradd/archive/3.2.zip) and unzip it.
2. Unzip the downloaded release and copy it to the `ext` directory of your phpBB board.
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Look for `Bulk User Add` under the Disabled Extensions list and click its `Enable` link.

## Usage
1. Navigate in the ACP to `Extensions -> Bulk User Add -> Bulk User Add Settings`.
2. Apply the settings that you require.
3. Navigate in the ACP to `Extensions -> Bulk User Add -> Bulk User Add Process.
4. Process the CSV file containing the new users.

## Uninstall
1. Navigate in the ACP to `Customise -> Manage extensions`.
2. Click the `Disable` link for `Bulk User Add`.
3. To permanently uninstall, click `Delete Data`, then delete the bulkuseradd folder from `phpBB/ext/david63/`.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2020 - David Wood