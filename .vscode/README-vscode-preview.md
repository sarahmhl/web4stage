# Setup VS Code preview (1 clic)

Objectif: ouvrir le site PHP dans VS Code sans passer par Chrome.

## Prerequis

- Demarrer Apache dans XAMPP.
- Ouvrir ce dossier de projet dans VS Code.

## Installation recommandee

1. Quand VS Code propose l'extension `ryuta46.multi-command`, installe-la.
2. Ouvre les raccourcis clavier JSON (`Preferences: Open Keyboard Shortcuts (JSON)`).
3. Ajoute cette entree une seule fois:

```json
{
  "key": "ctrl+alt+w",
  "command": "multiCommand.web4stage.openPreview",
  "when": "editorTextFocus || !inputFocus"
}
```

## Usage quotidien

- Appuie sur `Ctrl+Alt+W` pour ouvrir l'apercu dans VS Code.
- URL chargee: `http://localhost/projet%20web/public/index.php/entry`

## Fallback sans extension

Si l'extension `multi-command` n'est pas installee:

1. Lance la commande `Simple Browser: Show`.
2. L'URL par defaut est deja pre-remplie via `simpleBrowser.defaultUrl`.
