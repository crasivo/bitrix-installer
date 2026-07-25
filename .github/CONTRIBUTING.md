# Contributing to crasivo/*-installer

Thank you for your interest in contributing to this project!

## How to Contribute

1. **Check guidelines**: Make sure to read [AGENTS.md](../AGENTS.md) which contains the core rules of our project (Zero
   Bloat, Dynamic Installation, isolation of public directories, cross-platform compatibility).
2. **Fork and Clone**: Fork the repository and clone it to your local machine.
3. **Make Changes**:
  - Keep files clean and lightweight.
  - Do not add external Composer dependencies without explicit consent.
  - Ensure Composer commands and script hooks are cross-platform (using PHP standard functions).
4. **Local Verification**: Test the command `composer create-project` or specific hooks locally to verify everything
   downloads and unpacks correctly.
5. **Submit a PR**: Open a Pull Request and fill out the PR template.
