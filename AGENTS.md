# Instructions for AI Agents

This repository is part of the `crasivo/*-installer` family of lightweight PHP skeleton/adapter projects. Their primary goal is to prepare CMS installations using standard Composer commands.

## Key Principles

1. **Zero Bloat**:
   - Do NOT add third-party Composer packages (such as Symfony components, PSR libraries, ORMs, etc.) unless explicitly requested by the user.
   - The installer must remain extremely lightweight, specifying only the required PHP version and PHP extensions in the `require` section of `composer.json`.

2. **Dynamic Installation Mechanism**:
   - The repository must NOT store the CMS source code itself.
   - All setup files, archives, or installer scripts must be downloaded dynamically during `composer create-project` using the `post-create-project-cmd` event.
   - Use cross-platform PHP one-liners or small PHP scripts in `composer.json` rather than OS-specific shell commands (e.g., avoid direct `curl`, `wget`, `unzip`, `rm`) to ensure compatibility with Windows, macOS, and Linux.

3. **Directory Structure & Isolation**:
   - Keep the project structure clean. The installer should prepare the workspace, separating web-accessible files (e.g., placing the installer script or entry point in a `public/` folder if the target architecture requires public directory isolation) from configuration and vendor files.
   - Avoid flat single-level structures that expose the `vendor/` directory or configuration files to the web root when public isolation is desired.

4. **Suite-wide Consistency**:
   - Maintain the same structure across all `crasivo/*-installer` repositories (`bitrix-installer`, `wordpress-installer`, `joomla-installer`, `opencart-installer`, `modx-installer`):
     - `composer.json` (metadata, system requirements, scripts)
     - `README.md` and `README_RU.md` (documentation in English and Russian)
     - `AGENTS.md` and `AGENTS_RU.md` (instructions for AI agents)
     - `.gitignore` and `.gitattributes`

## Guidelines for Modifying Code

- When editing `composer.json` scripts, ensure the commands are robust and check for file/directory existence before running copy or touch commands.
- Keep PHP requirements strictly checked (e.g. extension check in `composer.json`).
- Ensure any added installer scripts or downloaders handle network errors or invalid download payloads gracefully.
- Do not create arbitrary helper directories in the root unless they are part of the standard template.
