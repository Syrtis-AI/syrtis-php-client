from __future__ import annotations

from wexample_config.const.types import DictConfig
from wexample_wex_addon_dev_php.workdir.php_package_workdir import PhpPackageWorkdir


class AppWorkdir(PhpPackageWorkdir):
    def prepare_value(self, raw_value: DictConfig | None = None) -> DictConfig:
        from wexample_helpers.helpers.string import string_to_kebab_case

        raw_value = super().prepare_value(raw_value=raw_value)

        raw_value["git"] = {
            "main_branch": "main",
            "remote": [

            ]
        }

        return raw_value
