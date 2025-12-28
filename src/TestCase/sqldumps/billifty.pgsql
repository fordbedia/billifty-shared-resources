--
-- PostgreSQL database dump
--

\restrict VtyWgeDsVwydFZPfb2r22uUGWnPbN0bjohIEyBR0xeSUyQpT9rSHa7T8eRjB3IW

-- Dumped from database version 15.15 (Debian 15.15-1.pgdg13+1)
-- Dumped by pg_dump version 17.6 (Debian 17.6-0+deb13u1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

-- *not* creating schema, since initdb creates it


--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS '';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: business_profiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.business_profiles (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    payment_information_id bigint,
    name character varying(255) NOT NULL,
    legal_name character varying(255),
    email character varying(255),
    phone character varying(255),
    website character varying(255),
    tax_id character varying(255),
    license_no character varying(255),
    address_line1 character varying(255),
    address_line2 character varying(255),
    city character varying(255),
    state character varying(255),
    postal_code character varying(255),
    country character varying(255),
    logo_disk character varying(255) DEFAULT 'public'::character varying NOT NULL,
    logo_path character varying(255),
    branding_json json,
    is_test smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: business_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.business_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: business_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.business_profiles_id_seq OWNED BY public.business_profiles.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: clients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clients (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    company character varying(255),
    email character varying(255),
    phone character varying(255),
    tax_id character varying(255),
    license_no character varying(255),
    address_line1 character varying(255),
    address_line2 character varying(255),
    city character varying(255),
    state character varying(255),
    postal_code character varying(255),
    country character varying(255),
    meta json,
    is_test smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: clients_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clients_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clients_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clients_id_seq OWNED BY public.clients.id;


--
-- Name: color_scheme; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.color_scheme (
    id bigint NOT NULL,
    color_scheme_name character varying(255),
    slug character varying(255) NOT NULL,
    color character varying(255),
    preview_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: color_scheme_color; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.color_scheme_color (
    id bigint NOT NULL,
    color_scheme_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: color_scheme_color_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.color_scheme_color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: color_scheme_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.color_scheme_color_id_seq OWNED BY public.color_scheme_color.id;


--
-- Name: color_scheme_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.color_scheme_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: color_scheme_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.color_scheme_id_seq OWNED BY public.color_scheme.id;


--
-- Name: currency; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.currency (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    symbol character varying(255) NOT NULL,
    "precision" smallint NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: currency_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.currency_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: currency_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.currency_id_seq OWNED BY public.currency.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: invoice_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoice_items (
    id bigint NOT NULL,
    invoice_id bigint NOT NULL,
    "position" integer DEFAULT 1 NOT NULL,
    name character varying(255),
    description text,
    quantity numeric(12,4) DEFAULT '1'::numeric NOT NULL,
    unit character varying(255),
    unit_price_cents bigint DEFAULT '0'::bigint NOT NULL,
    line_discount_cents bigint DEFAULT '0'::bigint NOT NULL,
    line_discount_rate numeric(8,4) DEFAULT '0'::numeric NOT NULL,
    tax_rate numeric(8,4) DEFAULT '0'::numeric NOT NULL,
    tax_cents bigint DEFAULT '0'::bigint NOT NULL,
    line_total_cents bigint DEFAULT '0'::bigint NOT NULL,
    meta json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoice_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoice_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoice_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoice_items_id_seq OWNED BY public.invoice_items.id;


--
-- Name: invoice_template_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoice_template_categories (
    id bigint NOT NULL,
    slug character varying(255) NOT NULL,
    display_name character varying(255) NOT NULL,
    preview_url character varying(255) NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    metadata json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoice_template_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoice_template_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoice_template_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoice_template_categories_id_seq OWNED BY public.invoice_template_categories.id;


--
-- Name: invoice_template_versions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoice_template_versions (
    id bigint NOT NULL,
    invoice_template_id integer NOT NULL,
    version integer NOT NULL,
    changelog json,
    released_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoice_template_versions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoice_template_versions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoice_template_versions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoice_template_versions_id_seq OWNED BY public.invoice_template_versions.id;


--
-- Name: invoice_templates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoice_templates (
    id bigint NOT NULL,
    invoice_template_category_id integer,
    slug character varying(255) NOT NULL,
    display_name character varying(255) NOT NULL,
    current_version integer DEFAULT 1 NOT NULL,
    preview_url character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    metadata json,
    view character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoice_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoice_templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoice_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoice_templates_id_seq OWNED BY public.invoice_templates.id;


--
-- Name: invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoices (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    business_profile_id integer NOT NULL,
    client_id integer NOT NULL,
    invoice_template_id integer NOT NULL,
    color_scheme_id integer NOT NULL,
    currency_id integer NOT NULL,
    invoice_number character varying(255) NOT NULL,
    reference character varying(255),
    shipping_address text,
    issued_on date,
    issued_at date,
    due_on date,
    paid_at timestamp(0) without time zone,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    template_slug character varying(255),
    template_version integer DEFAULT 1 NOT NULL,
    theme_json json,
    subtotal_cents bigint DEFAULT '0'::bigint NOT NULL,
    discount_mode character varying(255) DEFAULT 'none'::character varying NOT NULL,
    discount_cents bigint DEFAULT '0'::bigint NOT NULL,
    discount_rate numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    tax_cents bigint DEFAULT '0'::bigint NOT NULL,
    shipping_cents bigint DEFAULT '0'::bigint NOT NULL,
    shipping_tax_rate numeric(6,3) DEFAULT '0'::numeric NOT NULL,
    shipping_tax_cents bigint DEFAULT '0'::bigint NOT NULL,
    total_cents bigint DEFAULT '0'::bigint NOT NULL,
    amount_due_cents bigint DEFAULT '0'::bigint NOT NULL,
    notes text,
    terms text,
    pdf_url character varying(255),
    render_snapshot_html text,
    meta json,
    pdf_path text,
    pdf_disk character varying(255),
    csv_path text,
    is_test smallint DEFAULT '0'::smallint NOT NULL,
    pdf_status character varying(255),
    pdf_generated_at timestamp(0) without time zone,
    pdf_error text,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT invoices_discount_mode_check CHECK (((discount_mode)::text = ANY ((ARRAY['none'::character varying, 'amount'::character varying, 'percent'::character varying, 'per-line'::character varying])::text[]))),
    CONSTRAINT invoices_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'issued'::character varying, 'sent'::character varying, 'partially'::character varying, 'paid'::character varying, 'void'::character varying])::text[])))
);


--
-- Name: invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoices_id_seq OWNED BY public.invoices.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: oauth_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_access_tokens (
    id character(80) NOT NULL,
    user_id bigint,
    client_id uuid NOT NULL,
    name character varying(255),
    scopes text,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_auth_codes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_auth_codes (
    id character(80) NOT NULL,
    user_id bigint NOT NULL,
    client_id uuid NOT NULL,
    scopes text,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_clients; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_clients (
    id uuid NOT NULL,
    owner_type character varying(255),
    owner_id bigint,
    name character varying(255) NOT NULL,
    secret character varying(255),
    provider character varying(255),
    redirect_uris text NOT NULL,
    grant_types text NOT NULL,
    revoked boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: oauth_device_codes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_device_codes (
    id character(80) NOT NULL,
    user_id bigint,
    client_id uuid NOT NULL,
    user_code character(8) NOT NULL,
    scopes text NOT NULL,
    revoked boolean NOT NULL,
    user_approved_at timestamp(0) without time zone,
    last_polled_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone
);


--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.oauth_refresh_tokens (
    id character(80) NOT NULL,
    access_token_id character(80) NOT NULL,
    revoked boolean NOT NULL,
    expires_at timestamp(0) without time zone
);


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: payment_information; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payment_information (
    id bigint NOT NULL,
    payment_method character varying(255),
    bank_name character varying(255),
    account_name character varying(255),
    account_number character varying(255),
    routing_number character varying(255),
    iban character varying(255),
    swift_code character varying(255),
    paypal_email character varying(255),
    stripe_payment_link character varying(255),
    cash_app character varying(255),
    notes text,
    is_test smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT payment_information_payment_method_check CHECK (((payment_method)::text = ANY ((ARRAY['bank_transfer'::character varying, 'paypal'::character varying, 'stripe'::character varying, 'cash_app'::character varying])::text[])))
);


--
-- Name: payment_information_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payment_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payment_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payment_information_id_seq OWNED BY public.payment_information.id;


--
-- Name: plan_capabilities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.plan_capabilities (
    id bigint NOT NULL,
    plan_id bigint NOT NULL,
    key character varying(255) NOT NULL,
    label character varying(255),
    type character varying(255) DEFAULT 'string'::character varying NOT NULL,
    value text,
    meta json,
    model_relationship character varying(255),
    description text,
    "group" character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: plan_capabilities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.plan_capabilities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: plan_capabilities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.plan_capabilities_id_seq OWNED BY public.plan_capabilities.id;


--
-- Name: plans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.plans (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    price_monthly numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    price_yearly numeric(8,2),
    is_default boolean DEFAULT false NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: plans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.plans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: plans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.plans_id_seq OWNED BY public.plans.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: stripe_webhook_events; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.stripe_webhook_events (
    id bigint NOT NULL,
    user_id bigint,
    stripe_customer_id character varying(255),
    stripe_subscription_id character varying(255),
    event_id character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    api_version character varying(255),
    livemode character varying(255),
    payload json NOT NULL,
    received_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: stripe_webhook_events_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.stripe_webhook_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: stripe_webhook_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.stripe_webhook_events_id_seq OWNED BY public.stripe_webhook_events.id;


--
-- Name: user_subscriptions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_subscriptions (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    plan_id bigint NOT NULL,
    plan_code character varying(255) NOT NULL,
    billing_cycle character varying(255) NOT NULL,
    stripe_customer_id character varying(255),
    stripe_subscription_id character varying(255),
    currency character varying(10) DEFAULT 'usd'::character varying NOT NULL,
    unit_amount integer,
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    starts_at timestamp(0) without time zone,
    renews_at timestamp(0) without time zone,
    cancels_at timestamp(0) without time zone,
    canceled_at timestamp(0) without time zone,
    raw_payload json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: user_subscriptions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_subscriptions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_subscriptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_subscriptions_id_seq OWNED BY public.user_subscriptions.id;


--
-- Name: user_template_settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_template_settings (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    business_profile_id bigint,
    default_template_slug character varying(255),
    default_template_version integer,
    default_theme_json json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: user_template_settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_template_settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_template_settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_template_settings_id_seq OWNED BY public.user_template_settings.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    plan_id bigint DEFAULT '1'::bigint,
    fname character varying(255),
    lname character varying(255),
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    provider character varying(255),
    provider_id character varying(255),
    stripe_customer_id character varying(255),
    avatar text,
    is_test smallint DEFAULT '0'::smallint NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: business_profiles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.business_profiles ALTER COLUMN id SET DEFAULT nextval('public.business_profiles_id_seq'::regclass);


--
-- Name: clients id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clients ALTER COLUMN id SET DEFAULT nextval('public.clients_id_seq'::regclass);


--
-- Name: color_scheme id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.color_scheme ALTER COLUMN id SET DEFAULT nextval('public.color_scheme_id_seq'::regclass);


--
-- Name: color_scheme_color id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.color_scheme_color ALTER COLUMN id SET DEFAULT nextval('public.color_scheme_color_id_seq'::regclass);


--
-- Name: currency id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currency ALTER COLUMN id SET DEFAULT nextval('public.currency_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: invoice_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items ALTER COLUMN id SET DEFAULT nextval('public.invoice_items_id_seq'::regclass);


--
-- Name: invoice_template_categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_template_categories ALTER COLUMN id SET DEFAULT nextval('public.invoice_template_categories_id_seq'::regclass);


--
-- Name: invoice_template_versions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_template_versions ALTER COLUMN id SET DEFAULT nextval('public.invoice_template_versions_id_seq'::regclass);


--
-- Name: invoice_templates id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_templates ALTER COLUMN id SET DEFAULT nextval('public.invoice_templates_id_seq'::regclass);


--
-- Name: invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices ALTER COLUMN id SET DEFAULT nextval('public.invoices_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: payment_information id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_information ALTER COLUMN id SET DEFAULT nextval('public.payment_information_id_seq'::regclass);


--
-- Name: plan_capabilities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plan_capabilities ALTER COLUMN id SET DEFAULT nextval('public.plan_capabilities_id_seq'::regclass);


--
-- Name: plans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plans ALTER COLUMN id SET DEFAULT nextval('public.plans_id_seq'::regclass);


--
-- Name: stripe_webhook_events id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stripe_webhook_events ALTER COLUMN id SET DEFAULT nextval('public.stripe_webhook_events_id_seq'::regclass);


--
-- Name: user_subscriptions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_subscriptions ALTER COLUMN id SET DEFAULT nextval('public.user_subscriptions_id_seq'::regclass);


--
-- Name: user_template_settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_template_settings ALTER COLUMN id SET DEFAULT nextval('public.user_template_settings_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: business_profiles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.business_profiles (id, user_id, payment_information_id, name, legal_name, email, phone, website, tax_id, license_no, address_line1, address_line2, city, state, postal_code, country, logo_disk, logo_path, branding_json, is_test, created_at, updated_at, deleted_at) FROM stdin;
1	4	1	Test Company LLC	Test Company LLC	test_company_llc@gmail.com	87365245111				129 Bernham street	\N	Houston	TX	1222	US	public	\N	\N	0	2025-12-27 02:23:29	2025-12-27 02:23:29	\N
2	4	1	ILLCity Clothing LLC	ILLCity Clothing LLC	illCityClothing@gmail.com	87365245311				7099 Blair Stone Rd	\N	Tallahasse	FL	32301	US	public	\N	\N	0	2025-12-27 02:23:29	2025-12-27 02:23:29	\N
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: clients; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.clients (id, user_id, name, company, email, phone, tax_id, license_no, address_line1, address_line2, city, state, postal_code, country, meta, is_test, created_at, updated_at, deleted_at) FROM stdin;
1	4	John Doe	EvaSoft LLC	johndoe@gmail.com	9876381234			7900 S Post Oak	\N	Houston	TX	77890	US	\N	1	2025-12-27 02:23:29	2025-12-27 02:23:29	\N
2	4	Harry Doe	Wee LLC	harry@gmail.com	9876316234			1922 Pleasant Groove Rd	\N	Houston	TX	77840	US	\N	1	2025-12-27 02:23:29	2025-12-27 02:23:29	\N
\.


--
-- Data for Name: color_scheme; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.color_scheme (id, color_scheme_name, slug, color, preview_url, created_at, updated_at) FROM stdin;
1	Ocean Blue	ocean	\N	/images/invoice-selection/ocean-blue.png	2025-12-27 02:23:29	2025-12-27 02:23:29
2	Forest Green	forest	\N	/images/invoice-selection/forest-green.png	2025-12-27 02:23:29	2025-12-27 02:23:29
3	Royal Purple	royal	\N	/images/invoice-selection/royal-purple.png	2025-12-27 02:23:29	2025-12-27 02:23:29
4	Crimson Red	crimson	\N	/images/invoice-selection/crimson-red.png	2025-12-27 02:23:29	2025-12-27 02:23:29
5	Sunset Orange	sunset	\N	/images/invoice-selection/sunset-orange.png	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: color_scheme_color; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.color_scheme_color (id, color_scheme_id, name, code, created_at, updated_at) FROM stdin;
1	3	main	#8B5CF6	2025-12-27 02:23:29	2025-12-27 02:23:29
2	3	light	#D8B4FE	2025-12-27 02:23:29	2025-12-27 02:23:29
3	3	extra_light	rgba(253, 242, 248, 0.3)	2025-12-27 02:23:29	2025-12-27 02:23:29
4	3	gradient_bg_1	90deg,rgba(147, 51, 234, 1) 0%, rgba(168, 85, 247, 0.67) 55%, rgba(236, 72, 153, 1) 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
5	3	table_tbody_color	#FDF2F8	2025-12-27 02:23:29	2025-12-27 02:23:29
6	3	gradient_bg_1_light	142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
7	1	main	#3B82F6	2025-12-27 02:23:29	2025-12-27 02:23:29
8	1	light	#93C5FD	2025-12-27 02:23:29	2025-12-27 02:23:29
9	1	extra_light	rgba(255, 255, 255, 0.3)	2025-12-27 02:23:29	2025-12-27 02:23:29
10	1	gradient_bg_1	90deg,#020024 0%, #090979 35%, #00D4FF 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
11	1	table_tbody_color		2025-12-27 02:23:29	2025-12-27 02:23:29
12	1	gradient_bg_1_light	142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
13	2	main	#22C55E	2025-12-27 02:23:29	2025-12-27 02:23:29
14	2	light	#86EFAC	2025-12-27 02:23:29	2025-12-27 02:23:29
15	2	extra_light	rgba(255, 255, 255, 0.3)	2025-12-27 02:23:29	2025-12-27 02:23:29
16	2	gradient_bg_1	90deg,#2A7B9B 0%, #57C785 50%, #EDDD53 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
17	2	table_tbody_color		2025-12-27 02:23:29	2025-12-27 02:23:29
18	2	gradient_bg_1_light	142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
19	4	main	#EF4444	2025-12-27 02:23:29	2025-12-27 02:23:29
20	4	light	#FCA5A5	2025-12-27 02:23:29	2025-12-27 02:23:29
21	4	extra_light	rgba(255, 255, 255, 0.3)	2025-12-27 02:23:29	2025-12-27 02:23:29
22	4	gradient_bg_1	90deg,rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 0.67) 55%, rgba(235, 143, 143, 1) 79%	2025-12-27 02:23:29	2025-12-27 02:23:29
23	4	table_tbody_color		2025-12-27 02:23:29	2025-12-27 02:23:29
24	4	gradient_bg_1_light		2025-12-27 02:23:29	2025-12-27 02:23:29
25	5	main	#F97316	2025-12-27 02:23:29	2025-12-27 02:23:29
26	5	light	#FDBA74	2025-12-27 02:23:29	2025-12-27 02:23:29
27	5	extra_light	rgba(255, 255, 255, 0.3)	2025-12-27 02:23:29	2025-12-27 02:23:29
28	5	gradient_bg_1	142deg,rgba(249, 115, 22, 1) 1%, rgba(253, 186, 116, 1) 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
29	5	table_tbody_color		2025-12-27 02:23:29	2025-12-27 02:23:29
30	5	gradient_bg_1_light	142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: currency; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.currency (id, code, name, symbol, "precision", is_active, created_at, updated_at) FROM stdin;
1	USD	United States Dollar	$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
2	EUR	Euro	€	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
3	GBP	British Pound Sterling	£	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
4	JPY	Japanese Yen	¥	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
5	AUD	Australian Dollar	A$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
6	CAD	Canadian Dollar	C$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
7	CHF	Swiss Franc	CHF	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
8	CNY	Chinese Yuan Renminbi	¥	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
9	HKD	Hong Kong Dollar	HK$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
10	NZD	New Zealand Dollar	NZ$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
11	SGD	Singapore Dollar	S$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
12	SEK	Swedish Krona	kr	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
13	NOK	Norwegian Krone	kr	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
14	DKK	Danish Krone	kr	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
15	INR	Indian Rupee	₹	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
16	KRW	South Korean Won	₩	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
17	ZAR	South African Rand	R	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
18	BRL	Brazilian Real	R$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
19	MXN	Mexican Peso	$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
20	PHP	Philippine Peso	₱	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
21	THB	Thai Baht	฿	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
22	AED	UAE Dirham	د.إ	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
23	SAR	Saudi Riyal	﷼	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
24	TRY	Turkish Lira	₺	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
25	RUB	Russian Ruble	₽	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
26	PLN	Polish Zloty	zł	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
27	HUF	Hungarian Forint	Ft	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
28	CZK	Czech Koruna	Kč	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
29	ILS	Israeli Shekel	₪	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
30	MYR	Malaysian Ringgit	RM	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
31	IDR	Indonesian Rupiah	Rp	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
32	VND	Vietnamese Dong	₫	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
33	PKR	Pakistani Rupee	₨	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
34	BDT	Bangladeshi Taka	৳	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
35	NGN	Nigerian Naira	₦	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
36	EGP	Egyptian Pound	£	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
37	KES	Kenyan Shilling	KSh	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
38	GHS	Ghanaian Cedi	₵	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
39	CLP	Chilean Peso	$	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
40	ARS	Argentine Peso	$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
41	COP	Colombian Peso	$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
42	PEN	Peruvian Sol	S/	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
43	UYU	Uruguayan Peso	$U	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
44	TWD	New Taiwan Dollar	NT$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
45	QAR	Qatari Riyal	﷼	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
46	BHD	Bahraini Dinar	.د.ب	3	t	2025-12-27 02:23:29	2025-12-27 02:23:29
47	OMR	Omani Rial	﷼	3	t	2025-12-27 02:23:29	2025-12-27 02:23:29
48	KWD	Kuwaiti Dinar	د.ك	3	t	2025-12-27 02:23:29	2025-12-27 02:23:29
49	LKR	Sri Lankan Rupee	Rs	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
50	MMK	Myanmar Kyat	K	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
51	NPR	Nepalese Rupee	₨	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
52	BND	Brunei Dollar	B$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
53	LAK	Lao Kip	₭	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
54	KHR	Cambodian Riel	៛	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
55	MOP	Macanese Pataca	MOP$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
56	BMD	Bermudian Dollar	$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
57	JMD	Jamaican Dollar	J$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
58	TTD	Trinidad and Tobago Dollar	TT$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
59	BBD	Barbadian Dollar	Bds$	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
60	XOF	West African CFA Franc	CFA	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
61	XAF	Central African CFA Franc	FCFA	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
62	MUR	Mauritian Rupee	₨	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
63	SCR	Seychellois Rupee	₨	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
64	TZS	Tanzanian Shilling	TSh	2	t	2025-12-27 02:23:29	2025-12-27 02:23:29
65	UGX	Ugandan Shilling	USh	0	t	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: invoice_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.invoice_items (id, invoice_id, "position", name, description, quantity, unit, unit_price_cents, line_discount_cents, line_discount_rate, tax_rate, tax_cents, line_total_cents, meta, created_at, updated_at) FROM stdin;
1	1	1	User Login Authentication	Create a functinality for the user where they all be needed for verification before they proceed.	1.0000		20000	0	0.0000	0.0000	0	20000	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
2	1	1	Landing Page Design	Home Page Design	2.0000		15050	0	0.0000	0.0000	0	30100	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
3	1	1	Logo Design	Logo Design	2.0000		5000	0	0.0000	0.0000	0	10000	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: invoice_template_categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.invoice_template_categories (id, slug, display_name, preview_url, sort_order, is_active, metadata, created_at, updated_at) FROM stdin;
1	modern	Modern	/images/invoice-selection/modern.png	1	t	[]	2025-12-27 02:23:29	2025-12-27 02:23:29
2	classic	Classic	/images/invoice-selection/classic.png	2	t	[]	2025-12-27 02:23:29	2025-12-27 02:23:29
3	minimal	Minimal	/images/invoice-selection/minimal.png	3	t	[]	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: invoice_template_versions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.invoice_template_versions (id, invoice_template_id, version, changelog, released_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: invoice_templates; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.invoice_templates (id, invoice_template_category_id, slug, display_name, current_version, preview_url, is_active, metadata, view, created_at, updated_at) FROM stdin;
1	1	moderno	Moderno	1	/images/templates/moderno.jpg	t	\N	modern.v1.moderno	2025-12-27 02:23:29	2025-12-27 02:23:29
2	1	neo	Neo	1	/images/templates/neo.jpg	t	\N	modern.v1.neo-columns	2025-12-27 02:23:29	2025-12-27 02:23:29
3	2	aurora	Aurora	1	/images/templates/aurora.jpg	t	\N	classic.v1.aurora	2025-12-27 02:23:29	2025-12-27 02:23:29
4	2	ledger	Ledger	1	/images/templates/ledger.jpg	t	\N	classic.v1.ledger	2025-12-27 02:23:29	2025-12-27 02:23:29
5	3	nexxus	Nexxus	1	/images/templates/nexxus.jpg	t	\N	minimal.v1.nexxus	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: invoices; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.invoices (id, user_id, business_profile_id, client_id, invoice_template_id, color_scheme_id, currency_id, invoice_number, reference, shipping_address, issued_on, issued_at, due_on, paid_at, status, template_slug, template_version, theme_json, subtotal_cents, discount_mode, discount_cents, discount_rate, tax_cents, shipping_cents, shipping_tax_rate, shipping_tax_cents, total_cents, amount_due_cents, notes, terms, pdf_url, render_snapshot_html, meta, pdf_path, pdf_disk, csv_path, is_test, pdf_status, pdf_generated_at, pdf_error, deleted_at, created_at, updated_at) FROM stdin;
1	4	1	1	1	1	1	INV-0001	\N	\N	\N	\N	\N	\N	draft	test-company-llc	1	\N	60100	none	0	0.00	0	0	0.000	0	60100	0			\N	\N	\N	\N	\N	\N	0	\N	\N	\N	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_plans_table	1
2	0001_01_01_000000_create_users_table	1
3	0001_01_01_000001_create_cache_table	1
4	0001_01_01_000001_user_subscriptions	1
5	0001_01_01_000002_create_jobs_table	1
6	2025_10_09_163456_create_oauth_auth_codes_table	1
7	2025_10_09_163457_create_oauth_access_tokens_table	1
8	2025_10_09_163458_create_oauth_refresh_tokens_table	1
9	2025_10_09_163459_create_oauth_clients_table	1
10	2025_10_09_163500_create_oauth_device_codes_table	1
11	2025_10_19_172528_create_table_payment_information	1
12	2025_10_20_034007_create_business_profiles_table	1
13	2025_10_20_035753_create_clients_table	1
14	2025_10_20_040134_create_invoice_templates_table	1
15	2025_10_20_040222_create_invoice_template_versions_table	1
16	2025_10_20_040630_create_user_template_settings_table	1
17	2025_10_20_041124_create_currency_table	1
18	2025_10_20_041125_create_invoices_table	1
19	2025_10_20_041828_create_invoice_items_table	1
20	2025_10_24_175951_create_color_scheme_color_table	1
21	2025_12_10_031329_create_migration_to_seed_plans_table	1
22	2025_12_10_032438_create_migration_to_seed_tests_and_categories	1
23	2025_12_10_171714_create_table_plan_capabilities	1
24	2025_12_10_172917_seed_plan_capabilities	1
25	2025_12_15_070922_stripe_webhook_events	1
\.


--
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.oauth_access_tokens (id, user_id, client_id, name, scopes, revoked, created_at, updated_at, expires_at) FROM stdin;
d2173f60ba91ca51d9817a7e4e13b8b179591861786e4357ecbbdb881c6b6f3344dbd4bdedabebd7	5	019b5da3-4797-722c-92f3-bc295c7f0716	Billifty Web App	[]	t	2025-12-27 02:29:06	2025-12-27 02:37:08	2026-06-27 02:29:06
53f4540fdf306eadc2d3adf75ea1793b230821ac1100249457f8f19ac6dfdac6ecac87dff00e3871	6	019b5da3-4797-722c-92f3-bc295c7f0716	Billifty Web App	[]	f	2025-12-27 02:37:37	2025-12-27 02:37:37	2026-06-27 02:37:37
\.


--
-- Data for Name: oauth_auth_codes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.oauth_auth_codes (id, user_id, client_id, scopes, revoked, expires_at) FROM stdin;
\.


--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.oauth_clients (id, owner_type, owner_id, name, secret, provider, redirect_uris, grant_types, revoked, created_at, updated_at) FROM stdin;
019b5da3-4797-722c-92f3-bc295c7f0716	\N	\N	Billifty Web App	$2y$12$GjMtdeUk5HjgfbXLsd.HeuInfuHK.xinisKv7eWE.Kyd/p2AJDHO2	users	[]	["personal_access"]	f	2025-12-27 02:29:00	2025-12-27 02:29:00
\.


--
-- Data for Name: oauth_device_codes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.oauth_device_codes (id, user_id, client_id, user_code, scopes, revoked, user_approved_at, last_polled_at, expires_at) FROM stdin;
\.


--
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.oauth_refresh_tokens (id, access_token_id, revoked, expires_at) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: payment_information; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.payment_information (id, payment_method, bank_name, account_name, account_number, routing_number, iban, swift_code, paypal_email, stripe_payment_link, cash_app, notes, is_test, created_at, updated_at, deleted_at) FROM stdin;
1	bank_transfer	BoFa	John Doe	123456789	12345678914662	\N	\N	\N	\N	\N	Test	1	2025-12-27 02:23:29	2025-12-27 02:23:29	\N
\.


--
-- Data for Name: plan_capabilities; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.plan_capabilities (id, plan_id, key, label, type, value, meta, model_relationship, description, "group", is_active, created_at, updated_at) FROM stdin;
1	1	max_business_profiles	Business Profiles	int	1	\N	businessProfiles	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
2	1	max_clients	Clients	int	5	\N	clients	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
3	1	max_invoices_per_month	Invoices per month	int	5	{"usage":"monthly"}	invoices	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
4	1	pdf_watermark	PDF Watermark	bool	true	\N	\N	“Powered by Billifty” watermark	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
5	1	email_watermark	Email Watermark	bool	true	\N	\N	Billifty branding in emails	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
6	1	custom_prefix	Custom Invoice Numbering	bool	false	\N	\N	Basic invoice numbering	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
7	1	custom_branding	Custom Brand Colors	bool	false	\N	\N	Basic invoice template	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
8	1	multi_templates	Templates	bool	false	\N	\N	Basic invoice template	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
9	1	logo_upload	Logo Upload	bool	false	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
10	1	automated_reminders	Automated Reminders	string	none	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
11	1	online_payments	Online Payments	bool	false	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
12	1	multi_currency	Multi-Currency	bool	false	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
13	1	analytics_tier	Analytics	string	basic	\N	\N	\N	features	f	2025-12-27 02:23:29	2025-12-27 02:23:29
14	1	email_branding	Email Branding	string	billifty_footer	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
15	1	templates_tier	Templates	string	basic	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
16	1	support_level	Support	string	email	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
17	1	cta_text1	\N	string	Perfect for trying out Billifty.	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
18	1	cta_btn	\N	string	Get started free	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
19	1	cta_upper_text	\N	string	Start here	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
20	1	cta_card_label	\N	string	\N	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
21	2	max_business_profiles	Business Profiles	int	3	\N	businessProfiles	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
22	2	max_clients	Clients	int	0	{"unlimited":true}	clients	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
23	2	max_invoices_per_month	Invoices per month	int	10	{"usage":"monthly"}	invoices	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
24	2	pdf_watermark	PDF Watermark	bool	false	\N	\N	No PDF watermark	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
25	2	email_watermark	Email Watermark	bool	true	\N	\N	Watermark on emails	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
26	2	custom_prefix	Custom Invoice Numbering	bool	true	\N	\N	Custom invoice numbering	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
27	2	custom_branding	Custom Brand Colors	bool	true	\N	\N	Custom brand colors	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
28	2	multi_templates	Templates	bool	true	\N	\N	Multiple invoice templates	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
29	2	logo_upload	Logo Upload	bool	true	\N	\N	Upload business logo	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
30	2	automated_reminders	Automated Reminders	string	manual	\N	\N	Manual reminders	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
31	2	online_payments	Online Payments	bool	false	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
32	2	multi_currency	Multi-Currency	bool	false	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
33	2	analytics_tier	Analytics	string	standard	\N	\N	\N	features	f	2025-12-27 02:23:29	2025-12-27 02:23:29
34	2	email_branding	Email Branding	string	small_footer	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
35	2	templates_tier	Templates	string	multiple	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
36	2	support_level	Support	string	email	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
37	2	cta_text1	\N	string	Everything you need to invoice clients professionally.	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
38	2	cta_btn	\N	string	Upgrade to Pro	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
39	2	cta_upper_text	\N	string	\N	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
40	2	cta_card_label	\N	string	BEST FOR FREELANCERS	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
41	3	max_business_profiles	Business Profiles	int	0	{"unlimited":true}	businessProfiles	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
42	3	max_clients	Clients	int	0	{"unlimited":true}	clients	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
43	3	max_invoices_per_month	Invoices per month	int	0	{"unlimited":true,"usage":"monthly"}	invoices	\N	limits	t	2025-12-27 02:23:29	2025-12-27 02:23:29
44	3	pdf_watermark	PDF Watermark	bool	false	\N	\N	No branding on PDFs	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
45	3	email_watermark	Email Watermark	bool	false	\N	\N	No branding on emails	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
46	3	custom_prefix	Custom Invoice Numbering	bool	true	\N	\N	Custom invoice numbering	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
47	3	custom_branding	Custom Brand Colors	bool	true	\N	\N	Custom brand colors	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
48	3	multi_templates	Templates	bool	true	\N	\N	All advanced templates	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
49	3	logo_upload	Logo Upload	bool	true	\N	\N	Upload business logo	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
50	3	automated_reminders	Automated Reminders	string	automatic	\N	\N	Automated invoice reminders	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
51	3	online_payments	Online Payments	bool	true	\N	\N	Online payment links	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
52	3	multi_currency	Multi-Currency	bool	true	\N	\N	Multi-currency support	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
53	3	analytics_tier	Analytics	string	advanced	\N	\N	Advanced analytics dashboard	features	f	2025-12-27 02:23:29	2025-12-27 02:23:29
54	3	email_branding	Email Branding	string	none	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
55	3	templates_tier	Templates	string	all_advanced	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
56	3	support_level	Support	string	priority	\N	\N	\N	features	t	2025-12-27 02:23:29	2025-12-27 02:23:29
57	3	cta_text1	\N	string	Unlimited invoicing with advanced automation.	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
58	3	cta_btn	\N	string	Go Premium	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
59	3	cta_upper_text	\N	string	For growing teams	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
60	3	cta_card_label	\N	string	\N	\N	\N	\N	marketing	t	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: plans; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.plans (id, code, name, description, price_monthly, price_yearly, is_default, sort_order, created_at, updated_at) FROM stdin;
1	free	Free	Try Billifty with limited clients and invoices.	0.00	\N	t	1	2025-12-27 02:23:29	2025-12-27 02:23:29
2	pro	Pro	For freelancers and small teams.	4.99	49.99	f	2	2025-12-27 02:23:29	2025-12-27 02:23:29
3	premium	Premium	Unlimited invoicing and automation.	9.99	99.99	f	3	2025-12-27 02:23:29	2025-12-27 02:23:29
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: stripe_webhook_events; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.stripe_webhook_events (id, user_id, stripe_customer_id, stripe_subscription_id, event_id, type, api_version, livemode, payload, received_at, created_at, updated_at) FROM stdin;
1	\N	\N	inpay_1SinMCEgxqQWB3tMVtzgJM7G	evt_1SinMyEgxqQWB3tMQzUPmIym	invoice_payment.paid	2025-11-17.clover	0	{"id":"evt_1SinMyEgxqQWB3tMQzUPmIym","object":"event","api_version":"2025-11-17.clover","created":1766803000,"data":{"object":{"id":"inpay_1SinMCEgxqQWB3tMVtzgJM7G","object":"invoice_payment","amount_paid":4999,"amount_requested":4999,"created":1766802948,"currency":"usd","invoice":"in_1SinM8EgxqQWB3tMoOoosBGd","is_default":true,"livemode":false,"payment":{"payment_intent":"pi_3SinM8EgxqQWB3tM14Q0qnJq","type":"payment_intent"},"status":"paid","status_transitions":{"canceled_at":null,"paid_at":1766802950}}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":null},"type":"invoice_payment.paid"}	2025-12-27 02:36:41	2025-12-27 02:36:41	2025-12-27 02:36:41
2	5	cus_Tg9fDe6Sw4U9Ky	pm_1SinN1EgxqQWB3tMHo6MQM4E	evt_1SinN6EgxqQWB3tM6VKqhowa	payment_method.attached	2025-11-17.clover	0	{"id":"evt_1SinN6EgxqQWB3tM6VKqhowa","object":"event","api_version":"2025-11-17.clover","created":1766803006,"data":{"object":{"id":"pm_1SinN1EgxqQWB3tMHo6MQM4E","object":"payment_method","allow_redisplay":"limited","billing_details":{"address":{"city":"Missouri City","country":"US","line1":"1822 Crescent Oak Drive","line2":null,"postal_code":"77459","state":"TX"},"email":"fordbedia@gmail.com","name":"Ed Bedia","phone":null,"tax_id":null},"created":1766803003,"customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"link":{"email":"fordbedia@gmail.com"},"livemode":false,"metadata":[],"type":"link"}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"payment_method.attached"}	2025-12-27 02:36:48	2025-12-27 02:36:48	2025-12-27 02:36:48
3	5	cus_Tg9fDe6Sw4U9Ky	sub_1SinN4EgxqQWB3tMRsJeCnzX	evt_1SinN6EgxqQWB3tMyY7r26Wg	customer.subscription.created	2025-11-17.clover	0	{"id":"evt_1SinN6EgxqQWB3tMyY7r26Wg","object":"event","api_version":"2025-11-17.clover","created":1766803008,"data":{"object":{"id":"sub_1SinN4EgxqQWB3tMRsJeCnzX","object":"subscription","application":null,"application_fee_percent":null,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null},"billing_cycle_anchor":1766803003,"billing_cycle_anchor_config":null,"billing_mode":{"flexible":{"proration_discounts":"included"},"type":"flexible","updated_at":1766802999},"billing_thresholds":null,"cancel_at":null,"cancel_at_period_end":false,"canceled_at":null,"cancellation_details":{"comment":null,"feedback":null,"reason":null},"collection_method":"charge_automatically","created":1766803003,"currency":"usd","customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"days_until_due":null,"default_payment_method":"pm_1SinN1EgxqQWB3tMHo6MQM4E","default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"ended_at":null,"invoice_settings":{"account_tax_ids":null,"issuer":{"type":"self"}},"items":{"object":"list","data":[{"id":"si_Tg9gVEt13vcv4c","object":"subscription_item","billing_thresholds":null,"created":1766803004,"current_period_end":1798339003,"current_period_start":1766803003,"discounts":[],"metadata":[],"plan":{"id":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","object":"plan","active":true,"amount":4999,"amount_decimal":"4999","billing_scheme":"per_unit","created":1765434840,"currency":"usd","interval":"year","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDtjy585w9uXZ","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"price":{"id":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","object":"price","active":true,"billing_scheme":"per_unit","created":1765434840,"currency":"usd","custom_unit_amount":null,"livemode":false,"lookup_key":null,"metadata":[],"nickname":null,"product":"prod_TaDtjy585w9uXZ","recurring":{"interval":"year","interval_count":1,"meter":null,"trial_period_days":null,"usage_type":"licensed"},"tax_behavior":"unspecified","tiers_mode":null,"transform_quantity":null,"type":"recurring","unit_amount":4999,"unit_amount_decimal":"4999"},"quantity":1,"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","tax_rates":[]}],"has_more":false,"total_count":1,"url":"/v1/subscription_items?subscription=sub_1SinN4EgxqQWB3tMRsJeCnzX"},"latest_invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","livemode":false,"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"next_pending_invoice_item_invoice":null,"on_behalf_of":null,"pause_collection":null,"payment_settings":{"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"network":null,"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null,"save_default_payment_method":"off"},"pending_invoice_item_interval":null,"pending_setup_intent":null,"pending_update":null,"plan":{"id":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","object":"plan","active":true,"amount":4999,"amount_decimal":"4999","billing_scheme":"per_unit","created":1765434840,"currency":"usd","interval":"year","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDtjy585w9uXZ","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"quantity":1,"schedule":null,"start_date":1766803003,"status":"active","test_clock":null,"transfer_data":null,"trial_end":null,"trial_settings":{"end_behavior":{"missing_payment_method":"create_invoice"}},"trial_start":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"customer.subscription.created"}	2025-12-27 02:36:48	2025-12-27 02:36:48	2025-12-27 02:36:48
4	5	cus_Tg9fDe6Sw4U9Ky	pi_3SinN2EgxqQWB3tM0p8UEPis	evt_3SinN2EgxqQWB3tM0qXgBz8g	payment_intent.succeeded	2025-11-17.clover	0	{"id":"evt_3SinN2EgxqQWB3tM0qXgBz8g","object":"event","api_version":"2025-11-17.clover","created":1766803006,"data":{"object":{"id":"pi_3SinN2EgxqQWB3tM0p8UEPis","object":"payment_intent","amount":4999,"amount_capturable":0,"amount_details":{"tip":[]},"amount_received":4999,"application":null,"application_fee_amount":null,"automatic_payment_methods":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","client_secret":"pi_3SinN2EgxqQWB3tM0p8UEPis_secret_lPBt4nfwSlhsMITBLrrtOI9Hy","confirmation_method":"automatic","created":1766803004,"currency":"usd","customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"description":"Subscription creation","excluded_payment_method_types":null,"last_payment_error":null,"latest_charge":"py_3SinN2EgxqQWB3tM0nIKDpbo","livemode":false,"metadata":[],"next_action":null,"on_behalf_of":null,"payment_details":{"customer_reference":null,"order_reference":"cs_test_b1Y6qdU5hu5ZigvGk"},"payment_method":"pm_1SinN1EgxqQWB3tMHo6MQM4E","payment_method_configuration_details":null,"payment_method_options":{"link":{"persistent_token":null,"setup_future_usage":"off_session"}},"payment_method_types":["link"],"processing":null,"receipt_email":null,"review":null,"setup_future_usage":"off_session","shipping":null,"source":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"payment_intent.succeeded"}	2025-12-27 02:36:48	2025-12-27 02:36:48	2025-12-27 02:36:48
5	5	cus_Tg9fDe6Sw4U9Ky	pi_3SinN2EgxqQWB3tM0p8UEPis	evt_3SinN2EgxqQWB3tM0nlfLvrs	payment_intent.created	2025-11-17.clover	0	{"id":"evt_3SinN2EgxqQWB3tM0nlfLvrs","object":"event","api_version":"2025-11-17.clover","created":1766803004,"data":{"object":{"id":"pi_3SinN2EgxqQWB3tM0p8UEPis","object":"payment_intent","amount":4999,"amount_capturable":0,"amount_details":{"tip":[]},"amount_received":0,"application":null,"application_fee_amount":null,"automatic_payment_methods":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","client_secret":"pi_3SinN2EgxqQWB3tM0p8UEPis_secret_lPBt4nfwSlhsMITBLrrtOI9Hy","confirmation_method":"automatic","created":1766803004,"currency":"usd","customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"description":"Subscription creation","excluded_payment_method_types":null,"last_payment_error":null,"latest_charge":null,"livemode":false,"metadata":[],"next_action":null,"on_behalf_of":null,"payment_method":null,"payment_method_configuration_details":null,"payment_method_options":{"amazon_pay":{"express_checkout_element_session_id":null},"card":{"installments":null,"mandate_options":null,"network":null,"request_three_d_secure":"automatic"},"cashapp":[],"klarna":{"preferred_locale":null},"link":{"persistent_token":null}},"payment_method_types":["amazon_pay","card","cashapp","klarna","link"],"processing":null,"receipt_email":null,"review":null,"setup_future_usage":"off_session","shipping":null,"source":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"requires_payment_method","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"payment_intent.created"}	2025-12-27 02:36:48	2025-12-27 02:36:48	2025-12-27 02:36:48
6	5	cus_Tg9fDe6Sw4U9Ky	sub_1SinN4EgxqQWB3tMRsJeCnzX	evt_1SinN6EgxqQWB3tMOf3Jzsuk	checkout.session.completed	2025-11-17.clover	0	{"id":"evt_1SinN6EgxqQWB3tMOf3Jzsuk","object":"event","api_version":"2025-11-17.clover","created":1766803008,"data":{"object":{"id":"cs_test_b1Y6qdU5hu5ZigvGkhu47r1VOD9VlRlASlITfBZxvAIvP6KGCaGDfIxlBm","object":"checkout.session","adaptive_pricing":{"enabled":false},"after_expiration":null,"allow_promotion_codes":true,"amount_subtotal":4999,"amount_total":4999,"automatic_tax":{"enabled":false,"liability":null,"provider":null,"status":null},"billing_address_collection":null,"branding_settings":{"background_color":"#ffffff","border_style":"rounded","button_color":"#0074d4","display_name":"Billifty sandbox","font_family":"default","icon":null,"logo":null},"cancel_url":"https://int.getinvoice.com/app/account/manage-subscription","client_reference_id":null,"client_secret":null,"collected_information":{"business_name":null,"individual_name":null,"shipping_details":null},"consent":null,"consent_collection":null,"created":1766802999,"currency":"usd","currency_conversion":null,"custom_fields":[],"custom_text":{"after_submit":null,"shipping_address":null,"submit":null,"terms_of_service_acceptance":null},"customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"customer_creation":null,"customer_details":{"address":{"city":"Missouri City","country":"US","line1":"1822 Crescent Oak Drive","line2":null,"postal_code":"77459","state":"TX"},"business_name":null,"email":"fordbedia@gmail.com","individual_name":null,"name":"Ed Bedia","phone":null,"tax_exempt":"none","tax_ids":[]},"customer_email":null,"discounts":[],"expires_at":1766889399,"invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","invoice_creation":null,"livemode":false,"locale":null,"metadata":{"plan_code":"pro","billing_cycle":"yearly","billifty_user_id":"5"},"mode":"subscription","origin_context":null,"payment_intent":null,"payment_link":null,"payment_method_collection":"always","payment_method_configuration_details":{"id":"pmc_1Sd1XIEgxqQWB3tMJxgIok4L","parent":null},"payment_method_options":{"card":{"request_three_d_secure":"automatic"}},"payment_method_types":["card","klarna","link","cashapp","amazon_pay"],"payment_status":"paid","permissions":null,"phone_number_collection":{"enabled":false},"recovered_from":null,"saved_payment_method_options":{"allow_redisplay_filters":["always"],"payment_method_remove":"disabled","payment_method_save":null},"setup_intent":null,"shipping_address_collection":null,"shipping_cost":null,"shipping_options":[],"status":"complete","submit_type":null,"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","success_url":"https://int.getinvoice.com/app/checkout/success?session_id={CHECKOUT_SESSION_ID}","total_details":{"amount_discount":0,"amount_shipping":0,"amount_tax":0},"ui_mode":"hosted","url":null,"wallet_options":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":null},"type":"checkout.session.completed"}	2025-12-27 02:36:48	2025-12-27 02:36:48	2025-12-27 02:36:48
7	5	cus_Tg9fDe6Sw4U9Ky	py_3SinN2EgxqQWB3tM0nIKDpbo	evt_3SinN2EgxqQWB3tM0sXfJq3I	charge.succeeded	2025-11-17.clover	0	{"id":"evt_3SinN2EgxqQWB3tM0sXfJq3I","object":"event","api_version":"2025-11-17.clover","created":1766803006,"data":{"object":{"id":"py_3SinN2EgxqQWB3tM0nIKDpbo","object":"charge","amount":4999,"amount_captured":4999,"amount_refunded":0,"application":null,"application_fee":null,"application_fee_amount":null,"balance_transaction":"txn_3SinN2EgxqQWB3tM0bO8D0Vo","billing_details":{"address":{"city":"Missouri City","country":"US","line1":"1822 Crescent Oak Drive","line2":null,"postal_code":"77459","state":"TX"},"email":"fordbedia@gmail.com","name":"Ed Bedia","phone":null,"tax_id":null},"calculated_statement_descriptor":"LINK","captured":true,"created":1766803006,"currency":"usd","customer":"cus_Tg9fDe6Sw4U9Ky","description":"Subscription creation","destination":null,"dispute":null,"disputed":false,"failure_balance_transaction":null,"failure_code":null,"failure_message":null,"fraud_details":[],"livemode":false,"metadata":[],"on_behalf_of":null,"order":null,"outcome":{"advice_code":null,"network_advice_code":null,"network_decline_code":null,"network_status":"approved_by_network","reason":null,"risk_level":"normal","risk_score":55,"seller_message":"Payment complete.","type":"authorized"},"paid":true,"payment_intent":"pi_3SinN2EgxqQWB3tM0p8UEPis","payment_method":"pm_1SinN1EgxqQWB3tMHo6MQM4E","payment_method_details":{"link":{"country":"US"},"type":"link"},"radar_options":[],"receipt_email":null,"receipt_number":null,"receipt_url":"https://pay.stripe.com/receipts/invoices/CAcaFwoVYWNjdF8xU2QxV2pFZ3hxUVdCM3RNKMCMvcoGMgb10MUPavE6LBYIoe0KU3cnvzwWkME4jeoUFjUL2EFD738dVE7AuMen0gX5SU9Xw9dassqF?s=ap","refunded":false,"review":null,"shipping":null,"source":null,"source_transfer":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"charge.succeeded"}	2025-12-27 02:36:48	2025-12-27 02:36:48	2025-12-27 02:36:48
8	5	cus_Tg9fDe6Sw4U9Ky	in_1SinN1EgxqQWB3tMjl3QF40c	evt_1SinN6EgxqQWB3tMCcg3Irxm	invoice.created	2025-11-17.clover	0	{"id":"evt_1SinN6EgxqQWB3tMCcg3Irxm","object":"event","api_version":"2025-11-17.clover","created":1766803007,"data":{"object":{"id":"in_1SinN1EgxqQWB3tMjl3QF40c","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":4999,"amount_overpaid":0,"amount_paid":4999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803003,"currency":"usd","custom_fields":null,"customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"customer_address":null,"customer_email":"fordbedia@gmail.com","customer_name":"Ed Bedia","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803003,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOA02007Yg3QEbo?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOA02007Yg3QEbo/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinN1EgxqQWB3tMFrgKgWuT","object":"line_item","amount":4999,"currency":"usd","description":"1 \\u00d7 Pro \\u2013 Yearly (at $49.99 / year)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","livemode":false,"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","subscription_item":"si_Tg9gVEt13vcv4c"},"type":"subscription_item_details"},"period":{"end":1798339003,"start":1766803003},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","product":"prod_TaDtjy585w9uXZ"},"type":"price_details","unit_amount_decimal":"4999"},"quantity":1,"subtotal":4999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinN1EgxqQWB3tMjl3QF40c/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"LJIMY8WA-0002","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803003,"period_start":1766803003,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803003,"marked_uncollectible_at":null,"paid_at":1766803006,"voided_at":null},"subtotal":4999,"subtotal_excluding_tax":4999,"test_clock":null,"total":4999,"total_discount_amounts":[],"total_excluding_tax":4999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"invoice.created"}	2025-12-27 02:36:49	2025-12-27 02:36:49	2025-12-27 02:36:49
9	5	cus_Tg9fDe6Sw4U9Ky	in_1SinN1EgxqQWB3tMjl3QF40c	evt_1SinN7EgxqQWB3tMyU0TReMn	invoice.finalized	2025-11-17.clover	0	{"id":"evt_1SinN7EgxqQWB3tMyU0TReMn","object":"event","api_version":"2025-11-17.clover","created":1766803007,"data":{"object":{"id":"in_1SinN1EgxqQWB3tMjl3QF40c","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":4999,"amount_overpaid":0,"amount_paid":4999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803003,"currency":"usd","custom_fields":null,"customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"customer_address":null,"customer_email":"fordbedia@gmail.com","customer_name":"Ed Bedia","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803003,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOQ0200Cubjx4DF?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOQ0200Cubjx4DF/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinN1EgxqQWB3tMFrgKgWuT","object":"line_item","amount":4999,"currency":"usd","description":"1 \\u00d7 Pro \\u2013 Yearly (at $49.99 / year)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","livemode":false,"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","subscription_item":"si_Tg9gVEt13vcv4c"},"type":"subscription_item_details"},"period":{"end":1798339003,"start":1766803003},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","product":"prod_TaDtjy585w9uXZ"},"type":"price_details","unit_amount_decimal":"4999"},"quantity":1,"subtotal":4999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinN1EgxqQWB3tMjl3QF40c/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"LJIMY8WA-0002","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803003,"period_start":1766803003,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803003,"marked_uncollectible_at":null,"paid_at":1766803006,"voided_at":null},"subtotal":4999,"subtotal_excluding_tax":4999,"test_clock":null,"total":4999,"total_discount_amounts":[],"total_excluding_tax":4999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"invoice.finalized"}	2025-12-27 02:36:49	2025-12-27 02:36:49	2025-12-27 02:36:49
10	5	cus_Tg9fDe6Sw4U9Ky	in_1SinN1EgxqQWB3tMjl3QF40c	evt_1SinN7EgxqQWB3tMMceWsjDL	invoice.paid	2025-11-17.clover	0	{"id":"evt_1SinN7EgxqQWB3tMMceWsjDL","object":"event","api_version":"2025-11-17.clover","created":1766803007,"data":{"object":{"id":"in_1SinN1EgxqQWB3tMjl3QF40c","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":4999,"amount_overpaid":0,"amount_paid":4999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803003,"currency":"usd","custom_fields":null,"customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"customer_address":null,"customer_email":"fordbedia@gmail.com","customer_name":"Ed Bedia","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803003,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOQ0200Cubjx4DF?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOQ0200Cubjx4DF/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinN1EgxqQWB3tMFrgKgWuT","object":"line_item","amount":4999,"currency":"usd","description":"1 \\u00d7 Pro \\u2013 Yearly (at $49.99 / year)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","livemode":false,"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","subscription_item":"si_Tg9gVEt13vcv4c"},"type":"subscription_item_details"},"period":{"end":1798339003,"start":1766803003},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","product":"prod_TaDtjy585w9uXZ"},"type":"price_details","unit_amount_decimal":"4999"},"quantity":1,"subtotal":4999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinN1EgxqQWB3tMjl3QF40c/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"LJIMY8WA-0002","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803003,"period_start":1766803003,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803003,"marked_uncollectible_at":null,"paid_at":1766803006,"voided_at":null},"subtotal":4999,"subtotal_excluding_tax":4999,"test_clock":null,"total":4999,"total_discount_amounts":[],"total_excluding_tax":4999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"invoice.paid"}	2025-12-27 02:36:49	2025-12-27 02:36:49	2025-12-27 02:36:49
11	5	cus_Tg9fDe6Sw4U9Ky	in_1SinN1EgxqQWB3tMjl3QF40c	evt_1SinN7EgxqQWB3tMdEMLxpTu	invoice.payment_succeeded	2025-11-17.clover	0	{"id":"evt_1SinN7EgxqQWB3tMdEMLxpTu","object":"event","api_version":"2025-11-17.clover","created":1766803007,"data":{"object":{"id":"in_1SinN1EgxqQWB3tMjl3QF40c","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":4999,"amount_overpaid":0,"amount_paid":4999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803003,"currency":"usd","custom_fields":null,"customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"customer_address":null,"customer_email":"fordbedia@gmail.com","customer_name":"Ed Bedia","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803003,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOQ0200Cubjx4DF?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzlnSW03MTlmNWNmQk1RR0JkNnJhS1BZTWJTeXE5LDE1NzM0MzgwOQ0200Cubjx4DF/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinN1EgxqQWB3tMFrgKgWuT","object":"line_item","amount":4999,"currency":"usd","description":"1 \\u00d7 Pro \\u2013 Yearly (at $49.99 / year)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","livemode":false,"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","subscription_item":"si_Tg9gVEt13vcv4c"},"type":"subscription_item_details"},"period":{"end":1798339003,"start":1766803003},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","product":"prod_TaDtjy585w9uXZ"},"type":"price_details","unit_amount_decimal":"4999"},"quantity":1,"subtotal":4999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinN1EgxqQWB3tMjl3QF40c/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"LJIMY8WA-0002","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"pro","billifty_user_id":"5","billing_cycle":"yearly"},"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803003,"period_start":1766803003,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803003,"marked_uncollectible_at":null,"paid_at":1766803006,"voided_at":null},"subtotal":4999,"subtotal_excluding_tax":4999,"test_clock":null,"total":4999,"total_discount_amounts":[],"total_excluding_tax":4999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"2181922c-84ce-49a4-ae6f-320fd10d319a"},"type":"invoice.payment_succeeded"}	2025-12-27 02:36:49	2025-12-27 02:36:49	2025-12-27 02:36:49
12	6	\N	cus_Tg9hoNzFjSmqxD	evt_1SinNuEgxqQWB3tMBGIPNLD4	customer.created	2025-11-17.clover	0	{"id":"evt_1SinNuEgxqQWB3tMBGIPNLD4","object":"event","api_version":"2025-11-17.clover","created":1766803058,"data":{"object":{"id":"cus_Tg9hoNzFjSmqxD","object":"customer","address":null,"balance":0,"created":1766803058,"currency":null,"customer_account":null,"default_source":null,"delinquent":false,"description":null,"discount":null,"email":"bastest@gmail.com","invoice_prefix":"BCSYRWKZ","invoice_settings":{"custom_fields":null,"default_payment_method":null,"footer":null,"rendering_options":null},"livemode":false,"metadata":{"billifty_user_id":"6"},"name":"Ed Bass Bas","next_invoice_sequence":1,"phone":null,"preferred_locales":[],"shipping":null,"tax_exempt":"none","test_clock":null}},"livemode":false,"pending_webhooks":2,"request":{"id":"req_FfAWL8ASMyQFwe","idempotency_key":"bae8304e-ab15-4b01-87c8-f9bacf9a5441"},"type":"customer.created"}	2025-12-27 02:37:38	2025-12-27 02:37:38	2025-12-27 02:37:38
13	\N	\N	inpay_1SinN5EgxqQWB3tM9lyZacPw	evt_1SinNxEgxqQWB3tMVRUCap8x	invoice_payment.paid	2025-11-17.clover	0	{"id":"evt_1SinNxEgxqQWB3tMVRUCap8x","object":"event","api_version":"2025-11-17.clover","created":1766803061,"data":{"object":{"id":"inpay_1SinN5EgxqQWB3tM9lyZacPw","object":"invoice_payment","amount_paid":4999,"amount_requested":4999,"created":1766803003,"currency":"usd","invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","is_default":true,"livemode":false,"payment":{"payment_intent":"pi_3SinN2EgxqQWB3tM0p8UEPis","type":"payment_intent"},"status":"paid","status_transitions":{"canceled_at":null,"paid_at":1766803006}}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":null},"type":"invoice_payment.paid"}	2025-12-27 02:37:41	2025-12-27 02:37:41	2025-12-27 02:37:41
14	6	cus_Tg9hoNzFjSmqxD	pm_1SinO5EgxqQWB3tM5HlJcsPo	evt_1SinO9EgxqQWB3tM3MxaR0wC	payment_method.attached	2025-11-17.clover	0	{"id":"evt_1SinO9EgxqQWB3tM3MxaR0wC","object":"event","api_version":"2025-11-17.clover","created":1766803071,"data":{"object":{"id":"pm_1SinO5EgxqQWB3tM5HlJcsPo","object":"payment_method","allow_redisplay":"limited","billing_details":{"address":{"city":"Missouri City","country":"US","line1":"1822 Crescent Oak Drive","line2":null,"postal_code":"77459","state":"TX"},"email":"bastest@gmail.com","name":"Ed Bedia","phone":null,"tax_id":null},"created":1766803069,"customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"link":{"email":"fordbedia@gmail.com"},"livemode":false,"metadata":[],"type":"link"}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"payment_method.attached"}	2025-12-27 02:37:53	2025-12-27 02:37:53	2025-12-27 02:37:53
15	6	\N	cus_Tg9hoNzFjSmqxD	evt_1SinO9EgxqQWB3tMgHQzipkj	customer.updated	2025-11-17.clover	0	{"id":"evt_1SinO9EgxqQWB3tMgHQzipkj","object":"event","api_version":"2025-11-17.clover","created":1766803069,"data":{"object":{"id":"cus_Tg9hoNzFjSmqxD","object":"customer","address":null,"balance":0,"created":1766803058,"currency":"usd","customer_account":null,"default_source":null,"delinquent":false,"description":null,"discount":null,"email":"bastest@gmail.com","invoice_prefix":"BCSYRWKZ","invoice_settings":{"custom_fields":null,"default_payment_method":null,"footer":null,"rendering_options":null},"livemode":false,"metadata":{"billifty_user_id":"6"},"name":"Ed Bass Bas","next_invoice_sequence":2,"phone":null,"preferred_locales":[],"shipping":null,"tax_exempt":"none","test_clock":null},"previous_attributes":{"currency":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"customer.updated"}	2025-12-27 02:37:53	2025-12-27 02:37:53	2025-12-27 02:37:53
16	6	cus_Tg9hoNzFjSmqxD	sub_1SinO8EgxqQWB3tMLkcBAUUR	evt_1SinO9EgxqQWB3tMaEThpwF6	customer.subscription.created	2025-11-17.clover	0	{"id":"evt_1SinO9EgxqQWB3tMaEThpwF6","object":"event","api_version":"2025-11-17.clover","created":1766803073,"data":{"object":{"id":"sub_1SinO8EgxqQWB3tMLkcBAUUR","object":"subscription","application":null,"application_fee_percent":null,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null},"billing_cycle_anchor":1766803069,"billing_cycle_anchor_config":null,"billing_mode":{"flexible":{"proration_discounts":"included"},"type":"flexible","updated_at":1766803058},"billing_thresholds":null,"cancel_at":null,"cancel_at_period_end":false,"canceled_at":null,"cancellation_details":{"comment":null,"feedback":null,"reason":null},"collection_method":"charge_automatically","created":1766803069,"currency":"usd","customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"days_until_due":null,"default_payment_method":"pm_1SinO5EgxqQWB3tM5HlJcsPo","default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"ended_at":null,"invoice_settings":{"account_tax_ids":null,"issuer":{"type":"self"}},"items":{"object":"list","data":[{"id":"si_Tg9h15yV0VOwil","object":"subscription_item","billing_thresholds":null,"created":1766803070,"current_period_end":1769481469,"current_period_start":1766803069,"discounts":[],"metadata":[],"plan":{"id":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","object":"plan","active":true,"amount":999,"amount_decimal":"999","billing_scheme":"per_unit","created":1765434874,"currency":"usd","interval":"month","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDuix6Cq3Omo9","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"price":{"id":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","object":"price","active":true,"billing_scheme":"per_unit","created":1765434874,"currency":"usd","custom_unit_amount":null,"livemode":false,"lookup_key":null,"metadata":[],"nickname":null,"product":"prod_TaDuix6Cq3Omo9","recurring":{"interval":"month","interval_count":1,"meter":null,"trial_period_days":null,"usage_type":"licensed"},"tax_behavior":"unspecified","tiers_mode":null,"transform_quantity":null,"type":"recurring","unit_amount":999,"unit_amount_decimal":"999"},"quantity":1,"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","tax_rates":[]}],"has_more":false,"total_count":1,"url":"/v1/subscription_items?subscription=sub_1SinO8EgxqQWB3tMLkcBAUUR"},"latest_invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","livemode":false,"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"next_pending_invoice_item_invoice":null,"on_behalf_of":null,"pause_collection":null,"payment_settings":{"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"network":null,"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null,"save_default_payment_method":"off"},"pending_invoice_item_interval":null,"pending_setup_intent":null,"pending_update":null,"plan":{"id":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","object":"plan","active":true,"amount":999,"amount_decimal":"999","billing_scheme":"per_unit","created":1765434874,"currency":"usd","interval":"month","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDuix6Cq3Omo9","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"quantity":1,"schedule":null,"start_date":1766803069,"status":"active","test_clock":null,"transfer_data":null,"trial_end":null,"trial_settings":{"end_behavior":{"missing_payment_method":"create_invoice"}},"trial_start":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"customer.subscription.created"}	2025-12-27 02:37:53	2025-12-27 02:37:53	2025-12-27 02:37:53
17	6	cus_Tg9hoNzFjSmqxD	pi_3SinO6EgxqQWB3tM1I5T1R4h	evt_3SinO6EgxqQWB3tM1JmDXpKe	payment_intent.succeeded	2025-11-17.clover	0	{"id":"evt_3SinO6EgxqQWB3tM1JmDXpKe","object":"event","api_version":"2025-11-17.clover","created":1766803071,"data":{"object":{"id":"pi_3SinO6EgxqQWB3tM1I5T1R4h","object":"payment_intent","amount":999,"amount_capturable":0,"amount_details":{"tip":[]},"amount_received":999,"application":null,"application_fee_amount":null,"automatic_payment_methods":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","client_secret":"pi_3SinO6EgxqQWB3tM1I5T1R4h_secret_yYx6zlO6C4RqngLtVZKBDVve6","confirmation_method":"automatic","created":1766803070,"currency":"usd","customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"description":"Subscription creation","excluded_payment_method_types":null,"last_payment_error":null,"latest_charge":"py_3SinO6EgxqQWB3tM1drd14UV","livemode":false,"metadata":[],"next_action":null,"on_behalf_of":null,"payment_details":{"customer_reference":null,"order_reference":"cs_test_b1zBPRow2pwkfdkDg"},"payment_method":"pm_1SinO5EgxqQWB3tM5HlJcsPo","payment_method_configuration_details":null,"payment_method_options":{"link":{"persistent_token":null,"setup_future_usage":"off_session"}},"payment_method_types":["link"],"processing":null,"receipt_email":null,"review":null,"setup_future_usage":"off_session","shipping":null,"source":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"payment_intent.succeeded"}	2025-12-27 02:37:53	2025-12-27 02:37:53	2025-12-27 02:37:53
18	6	cus_Tg9hoNzFjSmqxD	pi_3SinO6EgxqQWB3tM1I5T1R4h	evt_3SinO6EgxqQWB3tM1NV5Ko58	payment_intent.created	2025-11-17.clover	0	{"id":"evt_3SinO6EgxqQWB3tM1NV5Ko58","object":"event","api_version":"2025-11-17.clover","created":1766803070,"data":{"object":{"id":"pi_3SinO6EgxqQWB3tM1I5T1R4h","object":"payment_intent","amount":999,"amount_capturable":0,"amount_details":{"tip":[]},"amount_received":0,"application":null,"application_fee_amount":null,"automatic_payment_methods":null,"canceled_at":null,"cancellation_reason":null,"capture_method":"automatic","client_secret":"pi_3SinO6EgxqQWB3tM1I5T1R4h_secret_yYx6zlO6C4RqngLtVZKBDVve6","confirmation_method":"automatic","created":1766803070,"currency":"usd","customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"description":"Subscription creation","excluded_payment_method_types":null,"last_payment_error":null,"latest_charge":null,"livemode":false,"metadata":[],"next_action":null,"on_behalf_of":null,"payment_method":null,"payment_method_configuration_details":null,"payment_method_options":{"amazon_pay":{"express_checkout_element_session_id":null},"card":{"installments":null,"mandate_options":null,"network":null,"request_three_d_secure":"automatic"},"cashapp":[],"klarna":{"preferred_locale":null},"link":{"persistent_token":null}},"payment_method_types":["amazon_pay","card","cashapp","klarna","link"],"processing":null,"receipt_email":null,"review":null,"setup_future_usage":"off_session","shipping":null,"source":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"requires_payment_method","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"payment_intent.created"}	2025-12-27 02:37:53	2025-12-27 02:37:53	2025-12-27 02:37:53
19	6	cus_Tg9hoNzFjSmqxD	py_3SinO6EgxqQWB3tM1drd14UV	evt_3SinO6EgxqQWB3tM1bN97mGe	charge.succeeded	2025-11-17.clover	0	{"id":"evt_3SinO6EgxqQWB3tM1bN97mGe","object":"event","api_version":"2025-11-17.clover","created":1766803071,"data":{"object":{"id":"py_3SinO6EgxqQWB3tM1drd14UV","object":"charge","amount":999,"amount_captured":999,"amount_refunded":0,"application":null,"application_fee":null,"application_fee_amount":null,"balance_transaction":"txn_3SinO6EgxqQWB3tM1EHtSFN6","billing_details":{"address":{"city":"Missouri City","country":"US","line1":"1822 Crescent Oak Drive","line2":null,"postal_code":"77459","state":"TX"},"email":"bastest@gmail.com","name":"Ed Bedia","phone":null,"tax_id":null},"calculated_statement_descriptor":"LINK","captured":true,"created":1766803071,"currency":"usd","customer":"cus_Tg9hoNzFjSmqxD","description":"Subscription creation","destination":null,"dispute":null,"disputed":false,"failure_balance_transaction":null,"failure_code":null,"failure_message":null,"fraud_details":[],"livemode":false,"metadata":[],"on_behalf_of":null,"order":null,"outcome":{"advice_code":null,"network_advice_code":null,"network_decline_code":null,"network_status":"approved_by_network","reason":null,"risk_level":"normal","risk_score":26,"seller_message":"Payment complete.","type":"authorized"},"paid":true,"payment_intent":"pi_3SinO6EgxqQWB3tM1I5T1R4h","payment_method":"pm_1SinO5EgxqQWB3tM5HlJcsPo","payment_method_details":{"link":{"country":"US"},"type":"link"},"radar_options":[],"receipt_email":null,"receipt_number":null,"receipt_url":"https://pay.stripe.com/receipts/invoices/CAcaFwoVYWNjdF8xU2QxV2pFZ3hxUVdCM3RNKIKNvcoGMgbPr_1xfeY6LBYv7uuouHggpSxzqDKKFYgoo0sb7Ujl7JmOHdXQNmDWvvA6ZhkoXiwHCevP?s=ap","refunded":false,"review":null,"shipping":null,"source":null,"source_transfer":null,"statement_descriptor":null,"statement_descriptor_suffix":null,"status":"succeeded","transfer_data":null,"transfer_group":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"charge.succeeded"}	2025-12-27 02:37:54	2025-12-27 02:37:54	2025-12-27 02:37:54
20	6	cus_Tg9hoNzFjSmqxD	sub_1SinO8EgxqQWB3tMLkcBAUUR	evt_1SinOAEgxqQWB3tM3AfRylDY	checkout.session.completed	2025-11-17.clover	0	{"id":"evt_1SinOAEgxqQWB3tM3AfRylDY","object":"event","api_version":"2025-11-17.clover","created":1766803073,"data":{"object":{"id":"cs_test_b1zBPRow2pwkfdkDgfh629BOaEQYWW8OvMpxDQOHbTbLgwsB4G6kNY2DRF","object":"checkout.session","adaptive_pricing":{"enabled":false},"after_expiration":null,"allow_promotion_codes":true,"amount_subtotal":999,"amount_total":999,"automatic_tax":{"enabled":false,"liability":null,"provider":null,"status":null},"billing_address_collection":null,"branding_settings":{"background_color":"#ffffff","border_style":"rounded","button_color":"#0074d4","display_name":"Billifty sandbox","font_family":"default","icon":null,"logo":null},"cancel_url":"https://int.getinvoice.com/app/account/manage-subscription","client_reference_id":null,"client_secret":null,"collected_information":{"business_name":null,"individual_name":null,"shipping_details":null},"consent":null,"consent_collection":null,"created":1766803059,"currency":"usd","currency_conversion":null,"custom_fields":[],"custom_text":{"after_submit":null,"shipping_address":null,"submit":null,"terms_of_service_acceptance":null},"customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"customer_creation":null,"customer_details":{"address":{"city":"Missouri City","country":"US","line1":"1822 Crescent Oak Drive","line2":null,"postal_code":"77459","state":"TX"},"business_name":null,"email":"bastest@gmail.com","individual_name":null,"name":"Ed Bass Bas","phone":null,"tax_exempt":"none","tax_ids":[]},"customer_email":null,"discounts":[],"expires_at":1766889458,"invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","invoice_creation":null,"livemode":false,"locale":null,"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"mode":"subscription","origin_context":null,"payment_intent":null,"payment_link":null,"payment_method_collection":"always","payment_method_configuration_details":{"id":"pmc_1Sd1XIEgxqQWB3tMJxgIok4L","parent":null},"payment_method_options":{"card":{"request_three_d_secure":"automatic"}},"payment_method_types":["card","klarna","link","cashapp","amazon_pay"],"payment_status":"paid","permissions":null,"phone_number_collection":{"enabled":false},"recovered_from":null,"saved_payment_method_options":{"allow_redisplay_filters":["always"],"payment_method_remove":"disabled","payment_method_save":null},"setup_intent":null,"shipping_address_collection":null,"shipping_cost":null,"shipping_options":[],"status":"complete","submit_type":null,"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","success_url":"https://int.getinvoice.com/app/checkout/success?session_id={CHECKOUT_SESSION_ID}","total_details":{"amount_discount":0,"amount_shipping":0,"amount_tax":0},"ui_mode":"hosted","url":null,"wallet_options":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":null},"type":"checkout.session.completed"}	2025-12-27 02:37:54	2025-12-27 02:37:54	2025-12-27 02:37:54
21	6	cus_Tg9hoNzFjSmqxD	in_1SinO5EgxqQWB3tMJyCLCSV1	evt_1SinOAEgxqQWB3tMPTAfqRz7	invoice.created	2025-11-17.clover	0	{"id":"evt_1SinOAEgxqQWB3tMPTAfqRz7","object":"event","api_version":"2025-11-17.clover","created":1766803073,"data":{"object":{"id":"in_1SinO5EgxqQWB3tMJyCLCSV1","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":999,"amount_overpaid":0,"amount_paid":999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803069,"currency":"usd","custom_fields":null,"customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"customer_address":null,"customer_email":"bastest@gmail.com","customer_name":"Ed Bass Bas","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803069,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinO5EgxqQWB3tMpbl9317S","object":"line_item","amount":999,"currency":"usd","description":"1 \\u00d7 Premium \\u2013 Monthly (at $9.99 / month)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","livemode":false,"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","subscription_item":"si_Tg9h15yV0VOwil"},"type":"subscription_item_details"},"period":{"end":1769481469,"start":1766803069},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","product":"prod_TaDuix6Cq3Omo9"},"type":"price_details","unit_amount_decimal":"999"},"quantity":1,"subtotal":999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinO5EgxqQWB3tMJyCLCSV1/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"BCSYRWKZ-0001","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803069,"period_start":1766803069,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803069,"marked_uncollectible_at":null,"paid_at":1766803071,"voided_at":null},"subtotal":999,"subtotal_excluding_tax":999,"test_clock":null,"total":999,"total_discount_amounts":[],"total_excluding_tax":999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"invoice.created"}	2025-12-27 02:37:54	2025-12-27 02:37:54	2025-12-27 02:37:54
22	6	cus_Tg9hoNzFjSmqxD	in_1SinO5EgxqQWB3tMJyCLCSV1	evt_1SinOAEgxqQWB3tM5GwO15uN	invoice.finalized	2025-11-17.clover	0	{"id":"evt_1SinOAEgxqQWB3tM5GwO15uN","object":"event","api_version":"2025-11-17.clover","created":1766803073,"data":{"object":{"id":"in_1SinO5EgxqQWB3tMJyCLCSV1","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":999,"amount_overpaid":0,"amount_paid":999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803069,"currency":"usd","custom_fields":null,"customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"customer_address":null,"customer_email":"bastest@gmail.com","customer_name":"Ed Bass Bas","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803069,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinO5EgxqQWB3tMpbl9317S","object":"line_item","amount":999,"currency":"usd","description":"1 \\u00d7 Premium \\u2013 Monthly (at $9.99 / month)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","livemode":false,"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","subscription_item":"si_Tg9h15yV0VOwil"},"type":"subscription_item_details"},"period":{"end":1769481469,"start":1766803069},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","product":"prod_TaDuix6Cq3Omo9"},"type":"price_details","unit_amount_decimal":"999"},"quantity":1,"subtotal":999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinO5EgxqQWB3tMJyCLCSV1/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"BCSYRWKZ-0001","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803069,"period_start":1766803069,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803069,"marked_uncollectible_at":null,"paid_at":1766803071,"voided_at":null},"subtotal":999,"subtotal_excluding_tax":999,"test_clock":null,"total":999,"total_discount_amounts":[],"total_excluding_tax":999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"invoice.finalized"}	2025-12-27 02:37:54	2025-12-27 02:37:54	2025-12-27 02:37:54
23	6	cus_Tg9hoNzFjSmqxD	in_1SinO5EgxqQWB3tMJyCLCSV1	evt_1SinOAEgxqQWB3tMx0IMNOf2	invoice.paid	2025-11-17.clover	0	{"id":"evt_1SinOAEgxqQWB3tMx0IMNOf2","object":"event","api_version":"2025-11-17.clover","created":1766803073,"data":{"object":{"id":"in_1SinO5EgxqQWB3tMJyCLCSV1","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":999,"amount_overpaid":0,"amount_paid":999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803069,"currency":"usd","custom_fields":null,"customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"customer_address":null,"customer_email":"bastest@gmail.com","customer_name":"Ed Bass Bas","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803069,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinO5EgxqQWB3tMpbl9317S","object":"line_item","amount":999,"currency":"usd","description":"1 \\u00d7 Premium \\u2013 Monthly (at $9.99 / month)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","livemode":false,"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","subscription_item":"si_Tg9h15yV0VOwil"},"type":"subscription_item_details"},"period":{"end":1769481469,"start":1766803069},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","product":"prod_TaDuix6Cq3Omo9"},"type":"price_details","unit_amount_decimal":"999"},"quantity":1,"subtotal":999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinO5EgxqQWB3tMJyCLCSV1/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"BCSYRWKZ-0001","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803069,"period_start":1766803069,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803069,"marked_uncollectible_at":null,"paid_at":1766803071,"voided_at":null},"subtotal":999,"subtotal_excluding_tax":999,"test_clock":null,"total":999,"total_discount_amounts":[],"total_excluding_tax":999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"invoice.paid"}	2025-12-27 02:37:54	2025-12-27 02:37:54	2025-12-27 02:37:54
24	6	cus_Tg9hoNzFjSmqxD	in_1SinO5EgxqQWB3tMJyCLCSV1	evt_1SinOAEgxqQWB3tMjDUJ5hRO	invoice.payment_succeeded	2025-11-17.clover	0	{"id":"evt_1SinOAEgxqQWB3tMjDUJ5hRO","object":"event","api_version":"2025-11-17.clover","created":1766803073,"data":{"object":{"id":"in_1SinO5EgxqQWB3tMJyCLCSV1","object":"invoice","account_country":"US","account_name":"Billifty sandbox","account_tax_ids":null,"amount_due":999,"amount_overpaid":0,"amount_paid":999,"amount_remaining":0,"amount_shipping":0,"application":null,"attempt_count":0,"attempted":true,"auto_advance":false,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null,"provider":null,"status":null},"automatically_finalizes_at":null,"billing_reason":"subscription_create","collection_method":"charge_automatically","created":1766803069,"currency":"usd","custom_fields":null,"customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"customer_address":null,"customer_email":"bastest@gmail.com","customer_name":"Ed Bass Bas","customer_phone":null,"customer_shipping":null,"customer_tax_exempt":"none","customer_tax_ids":[],"default_payment_method":null,"default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"due_date":null,"effective_at":1766803069,"ending_balance":0,"footer":null,"from_invoice":null,"hosted_invoice_url":"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq?s=ap","invoice_pdf":"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9UZzloV3Iyam9jTmhSVDAxMkloZldvZHBJM25yd0NSLDE1NzM0Mzg3NA0200H8gPX9pq/pdf?s=ap","issuer":{"type":"self"},"last_finalization_error":null,"latest_revision":null,"lines":{"object":"list","data":[{"id":"il_1SinO5EgxqQWB3tMpbl9317S","object":"line_item","amount":999,"currency":"usd","description":"1 \\u00d7 Premium \\u2013 Monthly (at $9.99 / month)","discount_amounts":[],"discountable":true,"discounts":[],"invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","livemode":false,"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"parent":{"invoice_item_details":null,"subscription_item_details":{"invoice_item":null,"proration":false,"proration_details":{"credited_items":null},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","subscription_item":"si_Tg9h15yV0VOwil"},"type":"subscription_item_details"},"period":{"end":1769481469,"start":1766803069},"pretax_credit_amounts":[],"pricing":{"price_details":{"price":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","product":"prod_TaDuix6Cq3Omo9"},"type":"price_details","unit_amount_decimal":"999"},"quantity":1,"subtotal":999,"taxes":[]}],"has_more":false,"total_count":1,"url":"/v1/invoices/in_1SinO5EgxqQWB3tMJyCLCSV1/lines"},"livemode":false,"metadata":[],"next_payment_attempt":null,"number":"BCSYRWKZ-0001","on_behalf_of":null,"parent":{"quote_details":null,"subscription_details":{"metadata":{"plan_code":"premium","billifty_user_id":"6","billing_cycle":"monthly"},"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR"},"type":"subscription_details"},"payment_settings":{"default_mandate":null,"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null},"period_end":1766803069,"period_start":1766803069,"post_payment_credit_notes_amount":0,"pre_payment_credit_notes_amount":0,"receipt_number":null,"rendering":null,"shipping_cost":null,"shipping_details":null,"starting_balance":0,"statement_descriptor":null,"status":"paid","status_transitions":{"finalized_at":1766803069,"marked_uncollectible_at":null,"paid_at":1766803071,"voided_at":null},"subtotal":999,"subtotal_excluding_tax":999,"test_clock":null,"total":999,"total_discount_amounts":[],"total_excluding_tax":999,"total_pretax_credit_amounts":[],"total_taxes":[],"webhooks_delivered_at":null}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":"a11247cf-a3da-4a66-8526-e9fe0e91f656"},"type":"invoice.payment_succeeded"}	2025-12-27 02:37:54	2025-12-27 02:37:54	2025-12-27 02:37:54
25	\N	\N	inpay_1SinO9EgxqQWB3tMe16w3ivA	evt_1SinOpEgxqQWB3tMydfAXAdK	invoice_payment.paid	2025-11-17.clover	0	{"id":"evt_1SinOpEgxqQWB3tMydfAXAdK","object":"event","api_version":"2025-11-17.clover","created":1766803115,"data":{"object":{"id":"inpay_1SinO9EgxqQWB3tMe16w3ivA","object":"invoice_payment","amount_paid":999,"amount_requested":999,"created":1766803069,"currency":"usd","invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","is_default":true,"livemode":false,"payment":{"payment_intent":"pi_3SinO6EgxqQWB3tM1I5T1R4h","type":"payment_intent"},"status":"paid","status_transitions":{"canceled_at":null,"paid_at":1766803071}}},"livemode":false,"pending_webhooks":2,"request":{"id":null,"idempotency_key":null},"type":"invoice_payment.paid"}	2025-12-27 02:38:35	2025-12-27 02:38:35	2025-12-27 02:38:35
\.


--
-- Data for Name: user_subscriptions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.user_subscriptions (id, user_id, plan_id, plan_code, billing_cycle, stripe_customer_id, stripe_subscription_id, currency, unit_amount, status, starts_at, renews_at, cancels_at, canceled_at, raw_payload, created_at, updated_at) FROM stdin;
1	5	2	pro	yearly	cus_Tg9fDe6Sw4U9Ky	sub_1SinN4EgxqQWB3tMRsJeCnzX	usd	4999	active	\N	\N	\N	\N	{"id":"sub_1SinN4EgxqQWB3tMRsJeCnzX","object":"subscription","application":null,"application_fee_percent":null,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null},"billing_cycle_anchor":1766803003,"billing_cycle_anchor_config":null,"billing_mode":{"flexible":{"proration_discounts":"included"},"type":"flexible","updated_at":1766802999},"billing_thresholds":null,"cancel_at":null,"cancel_at_period_end":false,"canceled_at":null,"cancellation_details":{"comment":null,"feedback":null,"reason":null},"collection_method":"charge_automatically","created":1766803003,"currency":"usd","customer":"cus_Tg9fDe6Sw4U9Ky","customer_account":null,"days_until_due":null,"default_payment_method":"pm_1SinN1EgxqQWB3tMHo6MQM4E","default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"ended_at":null,"invoice_settings":{"account_tax_ids":null,"issuer":{"type":"self"}},"items":{"object":"list","data":[{"id":"si_Tg9gVEt13vcv4c","object":"subscription_item","billing_thresholds":null,"created":1766803004,"current_period_end":1798339003,"current_period_start":1766803003,"discounts":[],"metadata":[],"plan":{"id":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","object":"plan","active":true,"amount":4999,"amount_decimal":"4999","billing_scheme":"per_unit","created":1765434840,"currency":"usd","interval":"year","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDtjy585w9uXZ","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"price":{"id":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","object":"price","active":true,"billing_scheme":"per_unit","created":1765434840,"currency":"usd","custom_unit_amount":null,"livemode":false,"lookup_key":null,"metadata":[],"nickname":null,"product":"prod_TaDtjy585w9uXZ","recurring":{"interval":"year","interval_count":1,"meter":null,"trial_period_days":null,"usage_type":"licensed"},"tax_behavior":"unspecified","tiers_mode":null,"transform_quantity":null,"type":"recurring","unit_amount":4999,"unit_amount_decimal":"4999"},"quantity":1,"subscription":"sub_1SinN4EgxqQWB3tMRsJeCnzX","tax_rates":[]}],"has_more":false,"total_count":1,"url":"\\/v1\\/subscription_items?subscription=sub_1SinN4EgxqQWB3tMRsJeCnzX"},"latest_invoice":"in_1SinN1EgxqQWB3tMjl3QF40c","livemode":false,"metadata":{"billifty_user_id":"5","billing_cycle":"yearly","plan_code":"pro"},"next_pending_invoice_item_invoice":null,"on_behalf_of":null,"pause_collection":null,"payment_settings":{"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"network":null,"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null,"save_default_payment_method":"off"},"pending_invoice_item_interval":null,"pending_setup_intent":null,"pending_update":null,"plan":{"id":"price_1Sd3RsEgxqQWB3tM7ED1FYNn","object":"plan","active":true,"amount":4999,"amount_decimal":"4999","billing_scheme":"per_unit","created":1765434840,"currency":"usd","interval":"year","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDtjy585w9uXZ","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"quantity":1,"schedule":null,"start_date":1766803003,"status":"active","test_clock":null,"transfer_data":null,"trial_end":null,"trial_settings":{"end_behavior":{"missing_payment_method":"create_invoice"}},"trial_start":null}	2025-12-27 02:36:48	2025-12-27 02:36:48
2	6	3	premium	monthly	cus_Tg9hoNzFjSmqxD	sub_1SinO8EgxqQWB3tMLkcBAUUR	usd	999	active	\N	\N	\N	\N	{"id":"sub_1SinO8EgxqQWB3tMLkcBAUUR","object":"subscription","application":null,"application_fee_percent":null,"automatic_tax":{"disabled_reason":null,"enabled":false,"liability":null},"billing_cycle_anchor":1766803069,"billing_cycle_anchor_config":null,"billing_mode":{"flexible":{"proration_discounts":"included"},"type":"flexible","updated_at":1766803058},"billing_thresholds":null,"cancel_at":null,"cancel_at_period_end":false,"canceled_at":null,"cancellation_details":{"comment":null,"feedback":null,"reason":null},"collection_method":"charge_automatically","created":1766803069,"currency":"usd","customer":"cus_Tg9hoNzFjSmqxD","customer_account":null,"days_until_due":null,"default_payment_method":"pm_1SinO5EgxqQWB3tM5HlJcsPo","default_source":null,"default_tax_rates":[],"description":null,"discounts":[],"ended_at":null,"invoice_settings":{"account_tax_ids":null,"issuer":{"type":"self"}},"items":{"object":"list","data":[{"id":"si_Tg9h15yV0VOwil","object":"subscription_item","billing_thresholds":null,"created":1766803070,"current_period_end":1769481469,"current_period_start":1766803069,"discounts":[],"metadata":[],"plan":{"id":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","object":"plan","active":true,"amount":999,"amount_decimal":"999","billing_scheme":"per_unit","created":1765434874,"currency":"usd","interval":"month","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDuix6Cq3Omo9","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"price":{"id":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","object":"price","active":true,"billing_scheme":"per_unit","created":1765434874,"currency":"usd","custom_unit_amount":null,"livemode":false,"lookup_key":null,"metadata":[],"nickname":null,"product":"prod_TaDuix6Cq3Omo9","recurring":{"interval":"month","interval_count":1,"meter":null,"trial_period_days":null,"usage_type":"licensed"},"tax_behavior":"unspecified","tiers_mode":null,"transform_quantity":null,"type":"recurring","unit_amount":999,"unit_amount_decimal":"999"},"quantity":1,"subscription":"sub_1SinO8EgxqQWB3tMLkcBAUUR","tax_rates":[]}],"has_more":false,"total_count":1,"url":"\\/v1\\/subscription_items?subscription=sub_1SinO8EgxqQWB3tMLkcBAUUR"},"latest_invoice":"in_1SinO5EgxqQWB3tMJyCLCSV1","livemode":false,"metadata":{"billifty_user_id":"6","billing_cycle":"monthly","plan_code":"premium"},"next_pending_invoice_item_invoice":null,"on_behalf_of":null,"pause_collection":null,"payment_settings":{"payment_method_options":{"acss_debit":null,"bancontact":null,"card":{"network":null,"request_three_d_secure":"automatic"},"customer_balance":null,"konbini":null,"payto":null,"sepa_debit":null,"us_bank_account":null},"payment_method_types":null,"save_default_payment_method":"off"},"pending_invoice_item_interval":null,"pending_setup_intent":null,"pending_update":null,"plan":{"id":"price_1Sd3SQEgxqQWB3tMwm9eDbPZ","object":"plan","active":true,"amount":999,"amount_decimal":"999","billing_scheme":"per_unit","created":1765434874,"currency":"usd","interval":"month","interval_count":1,"livemode":false,"metadata":[],"meter":null,"nickname":null,"product":"prod_TaDuix6Cq3Omo9","tiers_mode":null,"transform_usage":null,"trial_period_days":null,"usage_type":"licensed"},"quantity":1,"schedule":null,"start_date":1766803069,"status":"active","test_clock":null,"transfer_data":null,"trial_end":null,"trial_settings":{"end_behavior":{"missing_payment_method":"create_invoice"}},"trial_start":null}	2025-12-27 02:37:54	2025-12-27 02:37:54
\.


--
-- Data for Name: user_template_settings; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.user_template_settings (id, user_id, business_profile_id, default_template_slug, default_template_version, default_theme_json, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, plan_id, fname, lname, name, email, email_verified_at, password, provider, provider_id, stripe_customer_id, avatar, is_test, remember_token, created_at, updated_at) FROM stdin;
1	1	\N	\N	John Paine	john+free@billifty.czom	\N	$2y$12$gQUmBYjPBjPKFovEFQjUkuj4LqHIRTxngPm4R/YMVvxqvyKoBWCba	\N	\N	\N	\N	0	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
2	2	\N	\N	Kirk McDonald	kirk+pro@billifty.com	\N	$2y$12$qqeJbS9ZW9ZUrOZP0Vf2sO/QevaNqC/yNm37VyU7q/mgtWbgqbfVe	\N	\N	\N	\N	0	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
3	3	\N	\N	James Harris	james+premium@billifty.com	\N	$2y$12$WSNZOUCJ3rhiz/YXHRItBOnGpTmKVgCpeXBWkClP.tVhgBiQyrfra	\N	\N	\N	\N	0	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
4	3	\N	\N	Ed Bedia	fordbedia@billifty.com	\N	$2y$12$BHWYmMunyRU4V87gYWyQoepcWbD81f8QKN5MJeT0685JTQnwovYsu	\N	\N	\N	\N	0	\N	2025-12-27 02:23:29	2025-12-27 02:23:29
5	2	\N	\N	Ed Bedia	fordbedia@gmail.com	\N	$2y$12$60huqB7zEm7KWIWeA8xU1Otp4HSBytqKm5eSg.qD1Lik4mCVsPBTq	google	107992215467446386255	cus_Tg9fDe6Sw4U9Ky	https://lh3.googleusercontent.com/a-/ALV-UjVMh8GQWSdcebj2VSvizmNnOq9WOQpQCqvBedFOLFDZnL-CtJFbQND9ZxMct0vuY27FY5yOjTE9QzGajy8DhgmWhtGbmi9shVbsNW7_uKNhc7tjd40SDH0Ghapsyo9B8tksg2eMx8c59IEefh2-ODsr7mvDQwnH9izN2wsjNOTvHhh09u0ZNaiQBt0hTpteOX53ACMyc_hYIP8KPtBTuCQmqTE4B3nir-n63wCBAsaX2yDEvqAlUPCUhHYJdn7hFp-Lz74fV-L-ksMG8EFH4gk0uL6BMZ6uyzt8JXyKBXXwVAnXkK6n3ojiDeLkuUOd36pEaZj2vkW0wh-xcWGC8M8MVyZpKJjlQ7N-sT9UeAjBHDcn8kHUxlsr-m0lQSckU7r7eQhieSYnxikZCRAAjLvpfwl6SlgITvN0mx5Sv5RAnC96_7TkNrjswCEgDQu76R3p6vngvC9sYDG3gWuyUGNk1XvUeWb7D_Jo0bJyVRzAVmuty27RaHK3Q3P4SvRfNaLJkEZe0MDwRvgqfPCiKkTMesN5ZYYDzsT5DAkOerkyyjgm0hUSQH2DZvFrK8rGjQFhfYw79NFBlAVl90NvBt57RtRRSENkFdFwy0Dt9bpGLZZBm6T_FAs1i1e9tQ_ZgHjnNFYuLiaiBgmVtGRL0GAtV2JqYq_bh_tcalDfTdfp0px2lbJ_mqD90186UHvwOUJLW1xjIaiAdUp1GRHfZRLxwfVRIS4tXx5ZG9paRPZBv0s_zVWdzdS4zvtxIwKn9GWEywafcP4ANKRQOfiuJvpDzFuFuc-GvpGVTVFEwVTbE7Cmt16XDE7D4_YKulRCFW930zkbqZ1AT5XdvT4Gvkv7B3OqHxgFVhs9MN0ofS_FspGCkOLwNnCCclJC_MOsg-PFLcFeYvrZ9YVMbDlhrmiYh2RGIJLj26_Ak_g2v1doDynymoO1HdTaTVoks7RRrAYjRyGUBc2TzZigJ-QnyutszA3h_353roZ1UnyWUwes6Bl_0CslsPR5-XltGNsvsEkd-bfUg8v9kL7BAFh1h0eWq3EaajVdUeBWrAGGb29YIKSLX5eYPpP8=s96-c	0	RwS1mtrkvNtYLBItN498cR5A41OcWSjftXHnzsY2ijll2W2L64QzFFSlLLwa	2025-12-27 02:28:31	2025-12-27 02:36:48
6	3	Ed Bass	Bas	Ed Bass Bas	bastest@gmail.com	\N	$2y$12$N3FGZEvBceOxO2G0uA0/F.a7w63ow9P1DhJEhT84.1Fmf9daTu1Mu	\N	\N	cus_Tg9hoNzFjSmqxD	\N	0	7Vz8GE9Y7WL3NqigdDnG7z8bsE4hVXDpdCrIwrKQBEsflYZ6Xrq2lCQzm1Q6	2025-12-27 02:37:37	2025-12-27 02:37:54
\.


--
-- Name: business_profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.business_profiles_id_seq', 2, true);


--
-- Name: clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.clients_id_seq', 2, true);


--
-- Name: color_scheme_color_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.color_scheme_color_id_seq', 30, true);


--
-- Name: color_scheme_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.color_scheme_id_seq', 5, true);


--
-- Name: currency_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.currency_id_seq', 65, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: invoice_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoice_items_id_seq', 3, true);


--
-- Name: invoice_template_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoice_template_categories_id_seq', 3, true);


--
-- Name: invoice_template_versions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoice_template_versions_id_seq', 1, false);


--
-- Name: invoice_templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoice_templates_id_seq', 5, true);


--
-- Name: invoices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoices_id_seq', 1, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 25, true);


--
-- Name: payment_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.payment_information_id_seq', 1, true);


--
-- Name: plan_capabilities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.plan_capabilities_id_seq', 60, true);


--
-- Name: plans_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.plans_id_seq', 3, true);


--
-- Name: stripe_webhook_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.stripe_webhook_events_id_seq', 25, true);


--
-- Name: user_subscriptions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_subscriptions_id_seq', 2, true);


--
-- Name: user_template_settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_template_settings_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 6, true);


--
-- Name: business_profiles business_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.business_profiles
    ADD CONSTRAINT business_profiles_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: clients clients_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (id);


--
-- Name: color_scheme_color color_scheme_color_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.color_scheme_color
    ADD CONSTRAINT color_scheme_color_pkey PRIMARY KEY (id);


--
-- Name: color_scheme color_scheme_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.color_scheme
    ADD CONSTRAINT color_scheme_pkey PRIMARY KEY (id);


--
-- Name: currency currency_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.currency
    ADD CONSTRAINT currency_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: invoice_items invoice_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items
    ADD CONSTRAINT invoice_items_pkey PRIMARY KEY (id);


--
-- Name: invoice_template_categories invoice_template_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_template_categories
    ADD CONSTRAINT invoice_template_categories_pkey PRIMARY KEY (id);


--
-- Name: invoice_template_categories invoice_template_categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_template_categories
    ADD CONSTRAINT invoice_template_categories_slug_unique UNIQUE (slug);


--
-- Name: invoice_template_versions invoice_template_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_template_versions
    ADD CONSTRAINT invoice_template_versions_pkey PRIMARY KEY (id);


--
-- Name: invoice_templates invoice_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_templates
    ADD CONSTRAINT invoice_templates_pkey PRIMARY KEY (id);


--
-- Name: invoice_templates invoice_templates_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_templates
    ADD CONSTRAINT invoice_templates_slug_unique UNIQUE (slug);


--
-- Name: invoices invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: oauth_access_tokens oauth_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: oauth_auth_codes oauth_auth_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_auth_codes
    ADD CONSTRAINT oauth_auth_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_clients oauth_clients_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_clients
    ADD CONSTRAINT oauth_clients_pkey PRIMARY KEY (id);


--
-- Name: oauth_device_codes oauth_device_codes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_device_codes
    ADD CONSTRAINT oauth_device_codes_pkey PRIMARY KEY (id);


--
-- Name: oauth_device_codes oauth_device_codes_user_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_device_codes
    ADD CONSTRAINT oauth_device_codes_user_code_unique UNIQUE (user_code);


--
-- Name: oauth_refresh_tokens oauth_refresh_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payment_information payment_information_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payment_information
    ADD CONSTRAINT payment_information_pkey PRIMARY KEY (id);


--
-- Name: plan_capabilities plan_capabilities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plan_capabilities
    ADD CONSTRAINT plan_capabilities_pkey PRIMARY KEY (id);


--
-- Name: plan_capabilities plan_capabilities_plan_id_key_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plan_capabilities
    ADD CONSTRAINT plan_capabilities_plan_id_key_unique UNIQUE (plan_id, key);


--
-- Name: plans plans_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plans
    ADD CONSTRAINT plans_code_unique UNIQUE (code);


--
-- Name: plans plans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plans
    ADD CONSTRAINT plans_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: stripe_webhook_events stripe_webhook_events_event_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stripe_webhook_events
    ADD CONSTRAINT stripe_webhook_events_event_id_unique UNIQUE (event_id);


--
-- Name: stripe_webhook_events stripe_webhook_events_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stripe_webhook_events
    ADD CONSTRAINT stripe_webhook_events_pkey PRIMARY KEY (id);


--
-- Name: user_subscriptions user_subscriptions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_subscriptions
    ADD CONSTRAINT user_subscriptions_pkey PRIMARY KEY (id);


--
-- Name: user_subscriptions user_subscriptions_stripe_subscription_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_subscriptions
    ADD CONSTRAINT user_subscriptions_stripe_subscription_id_unique UNIQUE (stripe_subscription_id);


--
-- Name: user_template_settings user_template_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_template_settings
    ADD CONSTRAINT user_template_settings_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: invoice_items_position_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoice_items_position_index ON public.invoice_items USING btree ("position");


--
-- Name: invoices_user_invoice_unique; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_user_invoice_unique ON public.invoices USING btree (user_id, invoice_number);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: oauth_access_tokens_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_access_tokens_user_id_index ON public.oauth_access_tokens USING btree (user_id);


--
-- Name: oauth_auth_codes_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_auth_codes_user_id_index ON public.oauth_auth_codes USING btree (user_id);


--
-- Name: oauth_clients_owner_type_owner_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_clients_owner_type_owner_id_index ON public.oauth_clients USING btree (owner_type, owner_id);


--
-- Name: oauth_device_codes_client_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_device_codes_client_id_index ON public.oauth_device_codes USING btree (client_id);


--
-- Name: oauth_device_codes_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_device_codes_user_id_index ON public.oauth_device_codes USING btree (user_id);


--
-- Name: oauth_refresh_tokens_access_token_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX oauth_refresh_tokens_access_token_id_index ON public.oauth_refresh_tokens USING btree (access_token_id);


--
-- Name: plan_capabilities_is_active_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX plan_capabilities_is_active_index ON public.plan_capabilities USING btree (is_active);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: stripe_webhook_events_stripe_customer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stripe_webhook_events_stripe_customer_id_index ON public.stripe_webhook_events USING btree (stripe_customer_id);


--
-- Name: stripe_webhook_events_stripe_subscription_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stripe_webhook_events_stripe_subscription_id_index ON public.stripe_webhook_events USING btree (stripe_subscription_id);


--
-- Name: stripe_webhook_events_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stripe_webhook_events_type_index ON public.stripe_webhook_events USING btree (type);


--
-- Name: stripe_webhook_events_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX stripe_webhook_events_user_id_index ON public.stripe_webhook_events USING btree (user_id);


--
-- Name: user_subscriptions_user_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_subscriptions_user_id_status_index ON public.user_subscriptions USING btree (user_id, status);


--
-- Name: user_template_settings_default_template_slug_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX user_template_settings_default_template_slug_index ON public.user_template_settings USING btree (default_template_slug);


--
-- Name: users_provider_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_provider_id_index ON public.users USING btree (provider_id);


--
-- Name: users_stripe_customer_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_stripe_customer_id_index ON public.users USING btree (stripe_customer_id);


--
-- Name: business_profiles business_profiles_payment_information_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.business_profiles
    ADD CONSTRAINT business_profiles_payment_information_id_foreign FOREIGN KEY (payment_information_id) REFERENCES public.payment_information(id) ON DELETE CASCADE;


--
-- Name: business_profiles business_profiles_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.business_profiles
    ADD CONSTRAINT business_profiles_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: clients clients_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: color_scheme_color color_scheme_color_color_scheme_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.color_scheme_color
    ADD CONSTRAINT color_scheme_color_color_scheme_id_foreign FOREIGN KEY (color_scheme_id) REFERENCES public.color_scheme(id) ON DELETE CASCADE;


--
-- Name: invoice_items invoice_items_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items
    ADD CONSTRAINT invoice_items_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.invoices(id) ON DELETE CASCADE;


--
-- Name: invoice_template_versions invoice_template_versions_invoice_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_template_versions
    ADD CONSTRAINT invoice_template_versions_invoice_template_id_foreign FOREIGN KEY (invoice_template_id) REFERENCES public.invoice_templates(id) ON DELETE CASCADE;


--
-- Name: invoice_templates invoice_templates_invoice_template_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_templates
    ADD CONSTRAINT invoice_templates_invoice_template_category_id_foreign FOREIGN KEY (invoice_template_category_id) REFERENCES public.invoice_template_categories(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_business_profile_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_business_profile_id_foreign FOREIGN KEY (business_profile_id) REFERENCES public.business_profiles(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_client_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_client_id_foreign FOREIGN KEY (client_id) REFERENCES public.clients(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_color_scheme_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_color_scheme_id_foreign FOREIGN KEY (color_scheme_id) REFERENCES public.color_scheme(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_currency_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_currency_id_foreign FOREIGN KEY (currency_id) REFERENCES public.currency(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_invoice_template_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_invoice_template_id_foreign FOREIGN KEY (invoice_template_id) REFERENCES public.invoice_templates(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: plan_capabilities plan_capabilities_plan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.plan_capabilities
    ADD CONSTRAINT plan_capabilities_plan_id_foreign FOREIGN KEY (plan_id) REFERENCES public.plans(id) ON DELETE CASCADE;


--
-- Name: stripe_webhook_events stripe_webhook_events_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.stripe_webhook_events
    ADD CONSTRAINT stripe_webhook_events_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: user_subscriptions user_subscriptions_plan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_subscriptions
    ADD CONSTRAINT user_subscriptions_plan_id_foreign FOREIGN KEY (plan_id) REFERENCES public.plans(id) ON DELETE CASCADE;


--
-- Name: user_subscriptions user_subscriptions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_subscriptions
    ADD CONSTRAINT user_subscriptions_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: user_template_settings user_template_settings_business_profile_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_template_settings
    ADD CONSTRAINT user_template_settings_business_profile_id_foreign FOREIGN KEY (business_profile_id) REFERENCES public.business_profiles(id) ON DELETE CASCADE;


--
-- Name: user_template_settings user_template_settings_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_template_settings
    ADD CONSTRAINT user_template_settings_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users users_plan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_plan_id_foreign FOREIGN KEY (plan_id) REFERENCES public.plans(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict VtyWgeDsVwydFZPfb2r22uUGWnPbN0bjohIEyBR0xeSUyQpT9rSHa7T8eRjB3IW

