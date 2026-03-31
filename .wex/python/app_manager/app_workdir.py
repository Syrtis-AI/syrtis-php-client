from __future__ import annotations

from wexample_config.const.types import DictConfig
from wexample_wex_addon_dev_php.workdir.php_package_workdir import PhpPackageWorkdir


class AppWorkdir(PhpPackageWorkdir):
    def prepare_value(self, raw_value: DictConfig | None = None) -> DictConfig:
        from wexample_helpers.helpers.string import string_to_kebab_case

        raw_value = super().prepare_value(raw_value=raw_value)

        def _build_remote_gitlab(target: AppWorkdir) -> str:
            name = string_to_kebab_case(target.get_runtime_config().search("global.name").get_str())
            vendor = string_to_kebab_case(target.get_vendor_name())
            return f"ssh://git@gitlab.syrtis.ai:4567/syrtis-suite-php/{vendor}-{name}.git"

        raw_value["git"] = {
            "main_branch": "main",
            "remote": [
                {
                    "name": "origin",
                    "type": "gitlab",
                    "url": _build_remote_gitlab,
                    "create_remote": True,
                },
            ]
        }

        return raw_value
