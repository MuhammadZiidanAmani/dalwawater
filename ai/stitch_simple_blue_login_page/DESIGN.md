---
name: Azure Clarity
colors:
  surface: '#f8f9fa'
  surface-dim: '#d9dadb'
  surface-bright: '#f8f9fa'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f4f5'
  surface-container: '#edeeef'
  surface-container-high: '#e7e8e9'
  surface-container-highest: '#e1e3e4'
  on-surface: '#191c1d'
  on-surface-variant: '#424752'
  inverse-surface: '#2e3132'
  inverse-on-surface: '#f0f1f2'
  outline: '#727784'
  outline-variant: '#c2c6d4'
  surface-tint: '#115cb9'
  primary: '#003f87'
  on-primary: '#ffffff'
  primary-container: '#0056b3'
  on-primary-container: '#bbd0ff'
  inverse-primary: '#acc7ff'
  secondary: '#555f6b'
  on-secondary: '#ffffff'
  secondary-container: '#d9e3f1'
  on-secondary-container: '#5b6571'
  tertiary: '#3a434a'
  on-tertiary: '#ffffff'
  tertiary-container: '#515a62'
  on-tertiary-container: '#c8d1da'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d7e2ff'
  primary-fixed-dim: '#acc7ff'
  on-primary-fixed: '#001a40'
  on-primary-fixed-variant: '#004491'
  secondary-fixed: '#d9e3f1'
  secondary-fixed-dim: '#bdc7d5'
  on-secondary-fixed: '#131c26'
  on-secondary-fixed-variant: '#3e4853'
  tertiary-fixed: '#dbe4ed'
  tertiary-fixed-dim: '#bfc8d0'
  on-tertiary-fixed: '#141d23'
  on-tertiary-fixed-variant: '#3f484f'
  background: '#f8f9fa'
  on-background: '#191c1d'
  surface-variant: '#e1e3e4'
typography:
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
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
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.03em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  container-max-width: 440px
  gutter: 1.5rem
  margin-mobile: 1rem
  stack-sm: 0.5rem
  stack-md: 1rem
  stack-lg: 2rem
---

## Brand & Style
The design system is centered on trust, efficiency, and clarity. Targeted at professional environments and secure portals, it leverages a **Modern Minimalist** aesthetic to reduce cognitive load during the authentication process. 

The interface should evoke a sense of reliability and calm. This is achieved through generous whitespace, a restricted color palette, and high-quality typography. Visual flourishes are kept to a minimum, ensuring that the user's path to completion is unobstructed and intuitive.

## Colors
The palette is dominated by **Professional Blue**, used for primary actions and brand presence. **Light Blue** serves as a secondary accent for hover states, subtle backgrounds, and informational highlights.

The background uses a "Very Light Gray" (#f8f9fa) to soften the contrast compared to pure white, reducing eye strain while maintaining a clean look. Text color should primarily use a deep charcoal (#212529) to ensure high legibility against light backgrounds.

## Typography
This design system utilizes **Inter** for its systematic, utilitarian nature. The typeface's tall x-height and neutral personality ensure maximum readability at all sizes.

Headlines should use a tighter letter-spacing and heavier weights to establish a clear hierarchy. Body text is kept at a comfortable 16px for desktop, while labels and supporting text utilize slightly bolder weights (Medium/600) to remain distinct at smaller scales.

## Layout & Spacing
The layout follows a **Fixed Grid** approach for the login interface, centering a compact container on the screen. The standard container width is capped at 440px to prevent input fields from becoming excessively wide and difficult to scan.

We use an 8px spacing rhythm. Vertical stacking of elements should follow the `stack-` increments:
- **stack-sm (8px):** Between labels and their respective inputs.
- **stack-md (16px):** Between separate form fields.
- **stack-lg (32px):** Between the header/logo section and the form start.

On mobile, the layout transitions to a fluid model with 16px side margins to maximize space.

## Elevation & Depth
Hierarchy is established using **Ambient Shadows** and tonal layers. The primary login card should sit on a low elevation to feel grounded yet distinct from the light gray background.

- **Level 1 (Card):** A soft, diffused shadow (0px 4px 20px rgba(0, 0, 0, 0.05)) helps lift the white card from the neutral background.
- **Level 2 (Interactive):** Elements like buttons should use a slightly more pronounced shadow on hover to indicate interactability.
- **Level 0 (Inputs):** Input fields are recessed or flat, using a 1px border (#dee2e6) that thickens and changes color to Professional Blue upon focus.

## Shapes
The shape language is **Rounded**, using a 0.5rem (8px) base radius for buttons and input fields. This softens the professional tone, making the interface feel more approachable and modern. Larger containers, such as the main login card, should use `rounded-xl` (1.5rem / 24px) to create a friendly, contemporary frame for the content.

## Components
### Buttons
- **Primary:** Professional Blue background, white text. No border. On hover, darken the blue slightly.
- **Secondary/Ghost:** Transparent background with Professional Blue text. Used for "Forgot Password" or "Create Account" links.

### Input Fields
- **Default:** White background, 1px light gray border, 8px corner radius.
- **Focus:** 2px Professional Blue border with a very subtle Light Blue outer glow (3px spread).
- **Placeholder:** Medium gray (#adb5bd) text.

### Cards
- The main container should have a white background, 24px corner radius, and 40px internal padding to create a premium, spacious feel.

### Checkboxes
- Custom styled with an 8px corner radius. When checked, the box should fill with Professional Blue and display a white checkmark.

### Feedback Elements
- **Error States:** Border changes to #dc3545 (Red). Error text appears below the input in 12px Medium weight.