---
name: Dalwa Water Management
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#444653'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#757684'
  outline-variant: '#c4c5d5'
  surface-tint: '#3755c3'
  primary: '#00288e'
  on-primary: '#ffffff'
  primary-container: '#1e40af'
  on-primary-container: '#a8b8ff'
  inverse-primary: '#b8c4ff'
  secondary: '#505f76'
  on-secondary: '#ffffff'
  secondary-container: '#d0e1fb'
  on-secondary-container: '#54647a'
  tertiary: '#003853'
  on-tertiary: '#ffffff'
  tertiary-container: '#005074'
  on-tertiary-container: '#68c4ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dde1ff'
  primary-fixed-dim: '#b8c4ff'
  on-primary-fixed: '#001453'
  on-primary-fixed-variant: '#173bab'
  secondary-fixed: '#d3e4fe'
  secondary-fixed-dim: '#b7c8e1'
  on-secondary-fixed: '#0b1c30'
  on-secondary-fixed-variant: '#38485d'
  tertiary-fixed: '#c9e6ff'
  tertiary-fixed-dim: '#89ceff'
  on-tertiary-fixed: '#001e2f'
  on-tertiary-fixed-variant: '#004c6e'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 36px
    fontWeight: '700'
    lineHeight: 44px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  headline-sm:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
  pos-price:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 20px
  lg: 32px
  xl: 48px
  gutter: 16px
  margin-mobile: 16px
  margin-desktop: 32px
---

## Brand & Style
The brand personality is rooted in operational efficiency, reliability, and precision. As a utility management tool, the UI must prioritize utility over decoration, ensuring that cashiers and administrators can process high-volume transactions without cognitive fatigue. 

The design system adopts a **Modern Corporate/Minimalist** style. It utilizes heavy white space to separate data-dense areas and a refined color palette to guide the eye toward critical actions. The aesthetic is clean and high-performance, evoking a sense of calm authority and professional hygiene—essential traits for a water management service.

## Colors
The color strategy is designed for maximum functional contrast. 

- **Primary Blue:** A deep, professional blue used for primary actions, navigation states, and brand presence. It ensures that the most important buttons are immediately identifiable in a high-speed POS environment.
- **Secondary/Modern Grey:** Used for supporting text, icons, and non-interactive elements to prevent the UI from feeling cluttered.
- **Backgrounds:** A crisp white (#FFFFFF) is used for primary workspaces and cards, while a light neutral grey (#F8FAFC) is used for page backgrounds to provide subtle depth.
- **Semantic Colors:** Clear greens, ambers, and reds are reserved strictly for stock status (In Stock, Low Stock, Out of Stock) and payment confirmations.

## Typography
This design system utilizes **Inter** for its exceptional legibility and neutral, systematic tone. The hierarchy is structured to support rapid data scanning.

- **Headlines:** Use tight letter-spacing and bold weights to ground sections of the dashboard.
- **Numerical Data:** For POS price displays and inventory counts, a specific `pos-price` style is used to ensure clarity during checkout.
- **Labels:** Small, uppercase labels with increased letter-spacing are used for table headers and secondary metadata.
- **Mobile Adjustments:** On mobile devices, `display-lg` and `headline-lg` should scale down by 20% to maintain screen real estate while preserving the relative hierarchy.

## Layout & Spacing
The layout follows a **8px square grid system** to ensure mathematical consistency across all components.

- **Desktop:** Uses a 12-column fluid grid. The POS interface typically employs a "Fixed Sidebar / Fluid Content / Fixed Summary" layout pattern, keeping the cart or checkout totals always visible on the right.
- **Mobile-First:** The layout collapses into a single-column view. Action buttons are pinned to the bottom of the viewport (sticky footer) for easy thumb access.
- **Spacing Rhythm:** Use `md` (20px) for internal card padding and `sm` (12px) for spacing between related list items. This creates a clear visual grouping of data points.

## Elevation & Depth
To maintain the minimalist aesthetic, the design system avoids heavy shadows. Hierarchy is instead established through **Tonal Layers** and **Low-Contrast Outlines**.

- **Level 0 (Background):** Neutral Grey (#F8FAFC) creates the base canvas.
- **Level 1 (Cards/Surface):** Pure White (#FFFFFF) with a 1px border (#E2E8F0). This is the standard for product lists and dashboard stats.
- **Level 2 (Active/Modals):** A very soft, diffused shadow (0px 4px 12px rgba(0, 0, 0, 0.05)) is used only for elements that sit above the primary UI, such as dropdown menus or confirmation modals.
- **Interactive States:** Elements should lift slightly (using a 2px shadow) or shift color on hover to provide tactile feedback for mouse-based users.

## Shapes
The shape language is **Soft**. A 4px standard radius (`rounded-sm`) is used for smaller UI elements like checkboxes and input fields, while an 8px radius (`rounded-lg`) is used for cards and main action buttons. 

This subtle rounding balances the professional, rigid nature of a POS system with a modern, approachable feel. It avoids the playfulness of pill-shaped buttons in favor of a more structured, efficient look.

## Components
- **POS Buttons:** Large, high-contrast buttons (minimum height 48px for touch targets). The primary "Checkout" button should use the Primary Blue with white text.
- **Statistics Cards:** White backgrounds, 1px grey borders. Feature a large numerical value (Headline MD) and a secondary label. A small trend icon (up/down arrow) should be positioned in the corner.
- **Inventory List Items:** Horizontal rows with high vertical padding. Include a small thumbnail (optional), product name in Semibold, and price in Primary Blue.
- **Status Badges:** Small, rounded-pill containers with low-opacity background tints (e.g., 10% opacity) and 100% opacity text of the same color (Success/Warning/Danger).
- **Input Fields:** Large, clear text inputs with 1px borders that darken to Primary Blue on focus. Labels are always persistent above the field, never hidden as placeholders, to ensure fast data entry.
- **Data Tables:** Striped rows (alternating white and neutral grey) are recommended for long inventory lists to assist the eye in horizontal scanning.