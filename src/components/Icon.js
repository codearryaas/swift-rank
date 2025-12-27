/**
 * Icon Component
 *
 * Renders icons from lucide-react library wrapped in WordPress Icon component
 * Icons from https://lucide.dev/icons
 */

import {
	FileText,
	HelpCircle,
	Building2,
	User,
	ShoppingBag,
	Video,
	ChefHat,
	Podcast,
	Calendar,
	ListChecks,
	Briefcase,
	Star,
	ArrowRight,
	ChevronUp,
	ChevronDown,
	ChevronRight,
	ChevronLeft,
	Pencil,
	Lock,
	Image,
	Check,
	X,
	ExternalLink,
	BookOpen,
	MessageCircle,
	Shield,
	ShoppingCart,
	Store,
	RefreshCw,
	Code,
	Funnel,
	Settings,
	Brackets,
	Info,
	Plus,
	Users,
	Globe,
	Trash2,
	Hash,
	Circle,
	LayoutGrid,
	LayoutTemplate,
	Search,
	Zap,
	Sparkles,
	Layers,
	Rocket,
	ShieldCheck,
	Crown,
	Quote,
	UtensilsCrossed,
	Link,
	Filter,
	Braces,
	Clock,
	Code2,
	Headphones,
	CheckCircle2,
	Edit3,
	List,
	File,
	Minus,
	Monitor,
} from 'lucide-react';
import { Icon as WPIcon } from '@wordpress/components';

// Icon name to component mapping
const iconMap = {
	'file-text': FileText,
	'help-circle': HelpCircle,
	'building-2': Building2,
	'user': User,
	'shopping-bag': ShoppingBag,
	'video': Video,
	'chef-hat': ChefHat,
	'podcast': Podcast,
	'calendar': Calendar,
	'list-checks': ListChecks,
	'briefcase': Briefcase,
	'star': Star,
	'arrow-right': ArrowRight,
	'chevron-up': ChevronUp,
	'chevron-down': ChevronDown,
	'chevron-right': ChevronRight,
	'chevron-left': ChevronLeft,
	'pencil': Pencil,
	'lock': Lock,
	'image': Image,
	'check': Check,
	'x': X,
	'external-link': ExternalLink,
	'book-open': BookOpen,
	'message-circle': MessageCircle,
	'shield': Shield,
	'shopping-cart': ShoppingCart,
	'storefront': Store,
	'refresh-cw': RefreshCw,
	'code': Code,
	'funnel': Funnel,
	'settings': Settings,
	'brackets': Brackets,
	'info': Info,
	'plus': Plus,
	'users': Users,
	'globe': Globe,
	'trash-2': Trash2,
	'hash': Hash,
	'circle': Circle,
	'layout-grid': LayoutGrid,
	'layout-template': LayoutTemplate,
	'search': Search,
	'zap': Zap,
	'sparkles': Sparkles,
	'layers': Layers,
	'rocket': Rocket,
	'shield-check': ShieldCheck,
	'crown': Crown,
	'quote': Quote,
	'utensils-crossed': UtensilsCrossed,
	'link': Link,
	'filter': Filter,
	'braces': Braces,
	'clock': Clock,
	'code-2': Code2,
	'headphones': Headphones,
	'check-circle-2': CheckCircle2,
	'edit-3': Edit3,
	'list': List,
	'file': File,
	'minus': Minus,
	'monitor': Monitor,
};

const Icon = ({ name, size = 24, className = '', style = {}, color }) => {
	// Get the icon from the map
	const LucideIcon = iconMap[name];

	if (!LucideIcon) {
		console.warn(`Icon "${name}" not found in Icon component`);
		return null;
	}

	// Merge color into style if provided
	const iconStyle = color ? { ...style, color } : style;

	// Create a wrapper component for the Lucide icon
	const IconWrapper = () => (
		<LucideIcon
			size={size}
			strokeWidth={2}
			fill="none"
			stroke="currentColor"
		/>
	);

	return (
		<WPIcon
			icon={IconWrapper}
			size={size}
			className={`schema-icon ${className}`.trim()}
			style={iconStyle}
		/>
	);
};

export default Icon;
