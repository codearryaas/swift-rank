import { useState, useEffect } from '@wordpress/element';
import {
	CheckboxControl,
	SelectControl,
	Button,
	ToggleControl,
	Card,
	CardBody,
	CardHeader,
	FormTokenField
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

const ConditionsPanel = ( {
	includeConditions,
	excludeConditions,
	onIncludeChange,
	onExcludeChange,
	includedByDefault,
	onIncludedByDefaultChange
} ) => {
	const [ postTypes, setPostTypes ] = useState( [] );
	const [ posts, setPosts ] = useState( [] );
	const [ loadingPosts, setLoadingPosts ] = useState( false );

	useEffect( () => {
		// Fetch available post types using correct REST API
		apiFetch( { path: '/wp/v2/types?context=edit' } )
			.then( ( types ) => {
				const publicTypes = Object.entries( types )
					.filter( ( [ key, type ] ) => type.viewable && key !== 'attachment' )
					.map( ( [ key, type ] ) => ( {
						label: type.name,
						value: key,
						rest_base: type.rest_base || key
					} ) );
				setPostTypes( publicTypes );
			} )
			.catch( ( error ) => {
				console.error( 'Error fetching post types:', error );
				// Fallback to common post types
				setPostTypes( [
					{ label: 'Posts', value: 'post', rest_base: 'posts' },
					{ label: 'Pages', value: 'page', rest_base: 'pages' }
				] );
			} );
	}, [] );

	const updateIncludeCondition = ( field, value ) => {
		onIncludeChange( {
			...includeConditions,
			[ field ]: value
		} );
	};

	const updateExcludeCondition = ( field, value ) => {
		onExcludeChange( {
			...excludeConditions,
			[ field ]: value
		} );
	};

	const addIncludePostType = ( postType ) => {
		if ( postType && ! includeConditions.postTypes.includes( postType ) ) {
			updateIncludeCondition( 'postTypes', [ ...includeConditions.postTypes, postType ] );
		}
	};

	const removeIncludePostType = ( postType ) => {
		updateIncludeCondition(
			'postTypes',
			includeConditions.postTypes.filter( ( pt ) => pt !== postType )
		);
	};

	const addExcludePostType = ( postType ) => {
		if ( postType && ! excludeConditions.postTypes.includes( postType ) ) {
			updateExcludeCondition( 'postTypes', [ ...excludeConditions.postTypes, postType ] );
		}
	};

	const removeExcludePostType = ( postType ) => {
		updateExcludeCondition(
			'postTypes',
			excludeConditions.postTypes.filter( ( pt ) => pt !== postType )
		);
	};

	const searchPosts = ( search ) => {
		if ( search.length < 2 ) {
			return Promise.resolve( [] );
		}

		setLoadingPosts( true );
		return apiFetch( {
			path: `/wp/v2/search?search=${ encodeURIComponent( search ) }&type=post&per_page=20`
		} )
			.then( ( results ) => {
				const suggestions = results.map( ( result ) => ( {
					id: result.id,
					title: result.title
				} ) );
				setPosts( suggestions );
				setLoadingPosts( false );
				return suggestions.map( s => s.title );
			} )
			.catch( ( error ) => {
				console.error( 'Error searching posts:', error );
				setLoadingPosts( false );
				return [];
			} );
	};

	const getSelectedPostTitles = ( postIds ) => {
		if ( ! postIds || postIds.length === 0 ) {
			return [];
		}
		// Fetch titles for selected post IDs
		return postIds.map( id => {
			const found = posts.find( p => p.id === id );
			return found ? found.title : `Post #${ id }`;
		} );
	};

	return (
		<div className="conditions-panel">
			<ToggleControl
				label={ __( 'Include on whole site by default', 'swift-rank' ) }
				checked={ includedByDefault }
				onChange={ onIncludedByDefaultChange }
				help={ __(
					'When enabled, this schema will be included on all pages unless excluded. When disabled, it will only appear where specifically included.',
					'swift-rank'
				) }
			/>

			<Card style={ { marginTop: '20px' } }>
				<CardHeader>
					<strong>{ __( 'Include Conditions', 'swift-rank' ) }</strong>
				</CardHeader>
				<CardBody>
					<p className="description" style={ { marginBottom: '15px' } }>
						{ __(
							'Specify where this schema should be included. Leave all unchecked to include everywhere (default behavior).',
							'swift-rank'
						) }
					</p>

					<CheckboxControl
						label={ __( 'Front Page', 'swift-rank' ) }
						checked={ includeConditions.frontPage }
						onChange={ ( value ) => updateIncludeCondition( 'frontPage', value ) }
					/>

					<CheckboxControl
						label={ __( 'Home Page (Blog Index)', 'swift-rank' ) }
						checked={ includeConditions.homePage }
						onChange={ ( value ) => updateIncludeCondition( 'homePage', value ) }
					/>

					<div style={ { marginTop: '15px' } }>
						<strong>{ __( 'Post Types', 'swift-rank' ) }</strong>
						<SelectControl
						label=""
							value=""
							options={ [
								{ label: __( 'Select a post type to include...', 'swift-rank' ), value: '' },
								...postTypes
							] }
							onChange={ ( value ) => {
								if ( value ) {
									addIncludePostType( value );
								}
							} }
						/>

						{ includeConditions.postTypes && includeConditions.postTypes.length > 0 && (
							<div style={ { marginTop: '10px' } }>
								{ includeConditions.postTypes.map( ( postType ) => {
									const typeLabel = postTypes.find( ( pt ) => pt.value === postType )?.label || postType;
									return (
										<div
											key={ postType }
											style={ {
												display: 'flex',
												justifyContent: 'space-between',
												alignItems: 'center',
												padding: '8px 12px',
												background: '#f0f0f1',
												marginBottom: '5px',
												borderRadius: '4px'
											} }
										>
											<span>{ typeLabel }</span>
											<Button
												isSmall
												isDestructive
												onClick={ () => removeIncludePostType( postType ) }
											>
												{ __( 'Remove', 'swift-rank' ) }
											</Button>
										</div>
									);
								} ) }
							</div>
						) }
					</div>

					<div style={ { marginTop: '20px' } }>
						<strong>{ __( 'Specific Posts/Pages', 'swift-rank' ) }</strong>
						<p className="description" style={ { marginBottom: '8px' } }>
							{ __( 'Start typing to search and select specific posts or pages.', 'swift-rank' ) }
						</p>
						<FormTokenField
							value={ getSelectedPostTitles( includeConditions.specificPosts || [] ) }
							suggestions={ posts.map( p => p.title ) }
							onChange={ ( tokens ) => {
								// Convert titles back to IDs
								const selectedIds = tokens.map( token => {
									const found = posts.find( p => p.title === token );
									return found ? found.id : null;
								} ).filter( Boolean );
								updateIncludeCondition( 'specificPosts', selectedIds );
							} }
							onInputChange={ searchPosts }
							placeholder={ __( 'Type to search...', 'swift-rank' ) }
						/>
					</div>
				</CardBody>
			</Card>

			<Card style={ { marginTop: '20px' } }>
				<CardHeader>
					<strong>{ __( 'Exclude Conditions', 'swift-rank' ) }</strong>
				</CardHeader>
				<CardBody>
					<p className="description" style={ { marginBottom: '15px' } }>
						{ __(
							'Specify where this schema should NOT be included, even if it matches include conditions.',
							'swift-rank'
						) }
					</p>

					<CheckboxControl
						label={ __( 'Front Page', 'swift-rank' ) }
						checked={ excludeConditions.frontPage }
						onChange={ ( value ) => updateExcludeCondition( 'frontPage', value ) }
					/>

					<CheckboxControl
						label={ __( 'Home Page (Blog Index)', 'swift-rank' ) }
						checked={ excludeConditions.homePage }
						onChange={ ( value ) => updateExcludeCondition( 'homePage', value ) }
					/>

					<div style={ { marginTop: '15px' } }>
						<strong>{ __( 'Post Types', 'swift-rank' ) }</strong>
						<SelectControl
						label=""
							value=""
							options={ [
								{ label: __( 'Select a post type to exclude...', 'swift-rank' ), value: '' },
								...postTypes
							] }
							onChange={ ( value ) => {
								if ( value ) {
									addExcludePostType( value );
								}
							} }
						/>

						{ excludeConditions.postTypes && excludeConditions.postTypes.length > 0 && (
							<div style={ { marginTop: '10px' } }>
								{ excludeConditions.postTypes.map( ( postType ) => {
									const typeLabel = postTypes.find( ( pt ) => pt.value === postType )?.label || postType;
									return (
										<div
											key={ postType }
											style={ {
												display: 'flex',
												justifyContent: 'space-between',
												alignItems: 'center',
												padding: '8px 12px',
												background: '#f0f0f1',
												marginBottom: '5px',
												borderRadius: '4px'
											} }
										>
											<span>{ typeLabel }</span>
											<Button
												isSmall
												isDestructive
												onClick={ () => removeExcludePostType( postType ) }
											>
												{ __( 'Remove', 'swift-rank' ) }
											</Button>
										</div>
									);
								} ) }
							</div>
						) }
					</div>

					<div style={ { marginTop: '20px' } }>
						<strong>{ __( 'Specific Posts/Pages', 'swift-rank' ) }</strong>
						<p className="description" style={ { marginBottom: '8px' } }>
							{ __( 'Start typing to search and select specific posts or pages to exclude.', 'swift-rank' ) }
						</p>
						<FormTokenField
							value={ getSelectedPostTitles( excludeConditions.specificPosts || [] ) }
							suggestions={ posts.map( p => p.title ) }
							onChange={ ( tokens ) => {
								const selectedIds = tokens.map( token => {
									const found = posts.find( p => p.title === token );
									return found ? found.id : null;
								} ).filter( Boolean );
								updateExcludeCondition( 'specificPosts', selectedIds );
							} }
							onInputChange={ searchPosts }
							placeholder={ __( 'Type to search...', 'swift-rank' ) }
						/>
					</div>
				</CardBody>
			</Card>
		</div>
	);
};

export default ConditionsPanel;
