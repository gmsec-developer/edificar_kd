# EDIFICAR KD IMPORT JSON V1

## Objetivo

Definir el formato oficial de intercambio entre KitchenDraw y EDIFICAR.

Este formato reemplazara progresivamente la dependencia de XML, TXT y SCN.

## Flujo futuro

KitchenDraw
-> SDK / Bridge EDIFICAR
-> edificar_import.json
-> EDIFICAR

## Estructura general

```json
{
  "version": "1.0",
  "source": "kitchendraw_sdk",
  "generated_at": "2026-05-13T00:00:00",
  "header": {},
  "modules": [],
  "parts": []
}

{
  "project_name": "ADANA CASA 8",
  "client_name": "Cliente",
  "designer": "Disenador",
  "currency": "USD",
  "total_without_tax": 0,
  "total_with_tax": 0
}

{
  "number": 98,
  "reference": "1BF- 59",
  "description": "Modulo KitchenDraw",
  "quantity": 1,
  "dx": 590,
  "dy": 520,
  "dz": 450,
  "pvp": 0,
  "total": 0
}

{
  "line_number": 825,
  "module_number": 98,
  "module_reference": "1BF- 59",
  "type_code": 2,
  "part": "PUER 111",
  "quantity": 2,
  "length": 289,
  "width": 444,
  "thickness": 19,
  "material_code": "01007",
  "material_raw": "DURAPLAC NOVOKOR 19 mm _N25005 MILAN",
  "material": "DURAPLAC NOVOKOR 19 mm",
  "color_code": "N25005",
  "color_description": "MILAN"
}