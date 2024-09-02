import { defineConfig } from "vitepress";

// https://vitepress.dev/reference/site-config
export default defineConfig({
  lang: "en-US",
  title: "Lunar API Documentation",
  description: "API layer for Lunar ecommerce package",
  base: "/lunar-api/",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [{ text: "Home", link: "/" }],

    sidebar: [
      {
        text: "Getting started",
        items: [
          { text: "Overview", link: "/overview" },
          { text: "Installation", link: "/installation" },
          { text: "Configuration", link: "/configuration" },
          { text: "Initial Setup", link: "/initial-setup" },
          { text: "Upgrade Guide", link: "/upgrade-guide" },
        ],
        collapsed: false,
      },
      {
        text: "Reference",
        items: [
          { text: "Overview", link: "/reference/overview" },
          { text: "Addresses", link: "/reference/addresses" },
          { text: "Attributes", link: "/reference/attributes" },
          { text: "Auth", link: "/reference/auth" },
          { text: "Brands", link: "/reference/brands" },
          { text: "Carts", link: "/reference/carts" },
          { text: "Collections", link: "/reference/collections" },
          { text: "Customers", link: "/reference/customers" },
          { text: "Orders", link: "/reference/orders" },
          {
            text: "Payment Options",
            link: "/reference/payment-options",
          },
          { text: "Products", link: "/reference/products" },
          { text: "Users", link: "/reference/users" },
        ],
        collapsed: false,
      },
      {
        text: "Extending",
        items: [
          { text: "Overview", link: "/extending/overview" },
          { text: "Route groups", link: "/extending/route-groups" },
          { text: "Controllers", link: "/extending/controllers" },
          { text: "Models", link: "/extending/models" },
          { text: "Policies", link: "/extending/policies" },
          { text: "Queries", link: "/extending/queries" },
          { text: "Schemas", link: "/extending/schemas" },
          { text: "Resources", link: "/extending/resources" },
          { text: "Examples", link: "/extending/examples" },
        ],
        collapsed: false,
      },
      {
        text: "Extras",
        items: [
          {
            text: "Compatible packages",
            link: "/more/compatible-packages",
          },
          {
            text: "Userful resources",
            link: "/more/useful-resources",
          },
        ],
        collapsed: false,
      },
    ],

    socialLinks: [
      { icon: "github", link: "https://github.com/dystcz/lunar-api" },
      { icon: "twitter", link: "https://twitter.com/dystcz" },
    ],
  },
});
