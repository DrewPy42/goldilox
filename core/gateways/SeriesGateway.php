<?php
namespace gateways;
include __DIR__ . "/../autoload.php";

use interfaces\GatewayInterface;

class SeriesGateway extends GatewayInterface {

  protected function getTableName(): string
  {
    return "series_title";
  }

  protected function getPrimaryKeyName(): string
  {
    return "title_id";
  }

  protected function getFieldNames(): string
  {
    return "title_id,series_title,alt_title,issn,limited_series,publisher_id,comicage_id,series_price,series_value,
    series_value_gain,volume_count,issue_count,copy_count,notes,location,owner,active,created,edited,missing_issues,
    duplicate_issues";
  }

  protected function getEditableFieldNames(): string
  {
    return "series_title,alt_title,issn,limited_series,publisher_id,comicage_id,series_price,series_value,
    series_value_gain,volume_count,issue_count,copy_count,notes,location,owner,active,created,edited,missing_issues,
    duplicate_issues";
  }

  protected function getQueryJoins() : string {
      return '';
  }
}
